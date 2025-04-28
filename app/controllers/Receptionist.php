<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__DIR__, 2) . '/vendor/autoload.php';
class Receptionist extends Controller
{


    public function __construct()
    {
        // Check if the user is logged in as a receptionist
        $this->checkAuth('Receptionist');
    }

    public function index()
    {
        $announcementModel = new M_Announcement;
        $memberModel = new M_Member;
        $eventParticipantsModel = new M_EventParticipants;

        // Fetch the latest 4 announcements with admin names
        $members = $memberModel->countAll();
        $announcements = $announcementModel->findAllWithAdminNames(4);
        $recentMembers = $memberModel->countRecentMembers();
        $eventParticipants = $eventParticipantsModel->CountUniqueParticipants();

        $data = [
            'announcements' => $announcements,
            'members' => $members,
            'recentMembers' => $recentMembers,
            'eventParticipants' => $eventParticipants
        ];

        $this->view('receptionist/receptionist-dashboard', $data);
    }

    public function announcements()
    {

        $announcementModel = new M_Announcement;
        $announcements = $announcementModel->findAllWithAdminDetails();
        $data = [
            'announcements' => $announcements
        ];


        $this->view('receptionist/receptionist-announcements', $data);
    }

    public function trainers($action = null)
    {
        switch ($action) {
            case 'createTrainer':
                // Load the form view to create a trainer
                $this->view('receptionist/receptionist-createTrainer');
                break;

            case 'viewTrainer':
                // Load the view to view a trainer
                $trainerModel = new M_Trainer;
                $trainer = $trainerModel->findByTrainerId($_GET['id']);

                $data = [
                    'trainer' => $trainer
                ];

                $this->view('receptionist/receptionist-viewTrainer', $data);
                break;

            case 'salaryHistory':
                // Load the view to view a trainer's salary history
                $trainer_id = $_GET['id'];

                $this->view('receptionist/receptionist-trainerSalaryHistory');
                break;

            case 'trainerCalendar':
                // Load the view to view a trainer's calendar
                $trainer_id = $_GET['id'];

                $this->view('receptionist/receptionist-trainerCalendar');
                break;

            default:
                // Fetch all trainers and pass to the view
                $trainerModel = new M_Trainer;
                $trainers = $trainerModel->findAll('created_at');

                $data = [
                    'trainers' => $trainers
                ];

                $this->view('receptionist/receptionist-trainers', $data);
                break;
        }
    }

    public function members($action = null)
    {
        switch ($action) {
            case 'createMember':
                $model = new M_Membership_plan();
                $data['membership_plans'] = $model->findAll();

                // Load the form view to create a member
                $this->view('receptionist/receptionist-createMember');
                break;


            case 'viewMember':
                // Load the view to view a trainer
                $member_id = $_GET['id'];

                $memberModel = new M_Member;
                $member = $memberModel->findByMemberId($member_id);

                $membershipSubscriptionModel = new M_MembershipSubscriptions;
                $membershipSubscription = $membershipSubscriptionModel->findByMemberId($member_id);

                $data = [
                    'member' => $member,
                    'membershipSubscription' => $membershipSubscription
                ];

                $this->view('receptionist/receptionist-viewMember', $data);
                break;

            case 'memberAttendance':
                $this->view('receptionist/receptionist-memberAttendance');
                break;

            case 'memberPaymentHistory':
                $memberId = $_GET['id'];
                if ($memberId) {

                    $paymentModel = new M_PhysicalPayment(); 

                    $payment_history = $paymentModel->getPaymentHistory($memberId);

                    $data = [
                        'payment_history' => $payment_history,
                        'member_id' => $memberId
                    ];

                    $this->view('receptionist/receptionist-memberPaymentHistory', $data);
                } else {
                    $_SESSION['error'] = 'Member not found.';
                    redirect('receptionist/members');
                }
                break;

            case 'memberSupplements':
                $memberId = $_GET['id'];

                if ($memberId) {

                    $supplementSalesModel = new M_SupplementSales;

                    // Fetch the supplement records for the member
                    $supplementRecords = $supplementSalesModel->findByMemberId($memberId);

                    $data = [
                        'supplements' => $supplementRecords
                    ];

                    $this->view('receptionist/receptionist-viewSupplements', $data);
                } else {
                    $_SESSION['error'] = 'Member not found.';
                    redirect('receptionist/members');
                }
                break;

        
                
            default:
                // Fetch all members and pass to the view
                $memberModel = new M_Member;
                $members = $memberModel->findAll('created_at');

                $data = [
                    'members' => $members
                ];

                $this->view('receptionist/receptionist-members', $data);
                break;
        }
    }

    public function bookings($action = null)
    {
        $bookingModel = new M_Booking();
        $bookings = $bookingModel->bookingsForAdmin();
        $holidayModal = new M_Holiday();
        $holidays = $holidayModal->findAll();

        if ($action === 'api') {
            header('Content-Type: application/json');
            echo json_encode([
                'bookings' => $bookings,
                'holidays' => $holidays
            ]);
            exit;
        }
        $this->view('receptionist/receptionist-booking');
    }

    public function calendar()
    {
        $this->view('receptionist/receptionist-calendar');
    }

    public function holiday($action = null)
    {
        $holidayModal = new M_Holiday();
        $holidays = $holidayModal->findAll();
        $bookingModel = new M_Booking();
        $bookings = $bookingModel->findAll();

        if ($action === 'api') {
            header('Content-Type: application/json');
            echo json_encode([
                'holidays' => $holidays,
                'bookings' => $bookings
            ]);
            exit;
        } elseif ($action === 'add') {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                header('Content-Type: application/json');

                $date = $_POST['holidayDate'] ?? null;
                $reason = isset($_POST['holidayReason']) && $_POST['holidayReason'] !== '' ? $_POST['holidayReason'] : null;

                $existingHoliday = $holidayModal->first(['date' => $date]);
                if ($existingHoliday) {
                    echo json_encode(["success" => false, "message" => "A holiday already exists for this date"]);
                    exit;
                }

                $data = [
                    'date' => $date,
                    'reason' => $reason
                ];

                $result = $holidayModal->insert($data);

                echo json_encode([
                    "success" => $result ? true : false,
                    "message" => $result ? "Holiday added successfully!" : "Failed to add holiday"
                ]);
                exit;
            }

            // Ensure a response is always sent
            echo json_encode(["success" => false, "message" => "Invalid request"]);
            exit;
        } elseif ($action === "delete") {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $id = $_POST['id'];

                if ($holidayModal->delete($id)) {
                    echo json_encode(["success" => true, "message" => "Holiday deleted successfully!"]);
                    exit;
                } else {
                    echo json_encode(["success" => false, "message" => "Error deleting holiday."]);
                    exit;
                }
            }
            echo json_encode(["success" => false, "message" => "Invalid request."]);
            exit;
        } elseif ($action === 'edit') {
            header('Content-type: application/json');

            $id = $_POST['id'] ?? null;
            $reason = isset($_POST['reason']) && $_POST['reason'] !== '' ? $_POST['reason'] : null;

            if (!$id) {
                echo json_encode(["success" => false, "message" => "Missing required fields"]);
                exit;
            }

            $data = ['reason' => $reason];
            $result = $holidayModal->update($id, $data);

            echo json_encode(
                [
                    "success" => $result ? true : false,
                    "message" => $result ? "Holiday reason updated successfully!" : "Failed to update reason"
                ]
            );
            exit;
        } elseif ($action === 'conflict') {
            header('Content-type: application/json');

            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;

            if (!$id && !$status) {
                echo json_encode(["success" => false, "message" => "Missing required fields"]);
                exit;
            }

            $data = ['status' => $status];

            $result = $bookingModel->update($id, $data);

            echo json_encode(
                [
                    "success" => $result ? true : false,
                    "message" => $result ? "Booking  updated successfully!" : "Failed to update "
                ]
            );
            exit;
        }
        $this->view('receptionist/receptionist-holiday');
    }

    public function payment()
    {
        $plansModel = new M_Membership_plan();
        $plans = $plansModel->findAll();

        $data = [
            'plans' => $plans,
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $memberId = trim($_POST['member_id']);
            $planName = trim($_POST['plan']);
            $startDate = trim($_POST['start_date']);
            $endDate = trim($_POST['end_date']);

            $planModel = $plansModel->first(['plan' => $planName]);

            if ($planModel) {
                $planId = $planModel->membershipPlan_id;

                $paymentData = [
                    'member_id' => $memberId,
                    'plan_id' => $planId,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ];

                $physicalPaymentModel = new M_PhysicalPayment();
                if ($physicalPaymentModel->validate($paymentData)) {
                    if ($physicalPaymentModel->insert($paymentData)) {
                        $data['success'] = 'Payment record added successfully!';
                    } else {
                        $data['error'] = 'Error: Could not add payment record.';
                    }
                } else {
                    $data['error'] = implode('<br>', $physicalPaymentModel->getErrors());
                }
            } else {
                $data['error'] = 'Error: Selected membership plan not found.';
            }
        }

        $this->view('receptionist/receptionist-payment', $data);
    }


    public function event_payment()
    {
        $eventModel = new M_JoinEvent();
        $data['event'] = $eventModel->getEventdetails();

        $this->view('receptionist/event_payment', $data);
    }
    public function joinEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $eventModel = new M_JoinEvent();
            $eventDetailsModel = new M_Event();

            $data = [
                'event_id' => $_POST['event_id'],
                'full_name' => $_POST['full_name'],
                'nic' => $_POST['nic'],
                'email' => $_POST['email'],
                'is_member' => $_POST['is_member'],
                'membership_number' => $_POST['membership_number'] ?? '',
            ];

            if ($eventModel->validate($data)) {
                if ($eventModel->insert($data)) {
                    $eventDetails = $eventDetailsModel->getEventById($data['event_id']);
                    if ($eventDetails) {
                        $eventName = $eventDetails->name;
                        $eventDate = date('F j, Y', strtotime($eventDetails->event_date));
                        $startTime = date('g:i A', strtotime($eventDetails->start_time));
                        $eventLocation = $eventDetails->location;

                        $mail = new PHPMailer(true);
                        try {
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'amandanethmini100@gmail.com';
                            $mail->Password = 'niib zlpx xskb bmag';
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            $mail->setFrom('amandanethmini100@gmail.com', 'Life Touch Fitness');
                            $mail->addAddress($data['email'], $data['full_name']);

                            $mail->isHTML(true);
                            $mail->Subject = 'Confirmation: Event Registration';
                            $mail->Body = "
                            Dear {$data['full_name']},<br><br>
                            Thank you for registering for the event: <strong>{$eventName}</strong>.<br>
                            <ul>
                                <li><strong>Date:</strong> {$eventDate}</li>
                                <li><strong>Time:</strong> {$startTime}</li>
                                <li><strong>Location:</strong> {$eventLocation}</li>
                            </ul>
                            We look forward to seeing you there!<br><br>
                            Best regards,<br>
                            <strong>Life Touch Fitness Team</strong>
                        ";

                            $mail->send();

                            $_SESSION['success'] = 'Registration successful! A confirmation email has been sent.';
                            redirect('receptionist/event_payment');
                            exit();
                        } catch (Exception $e) {
                            $_SESSION['join_errors'] = ['Mailer Error: ' . $mail->ErrorInfo];
                            $_SESSION['form_data'] = $data;
                            redirect('receptionist/event_payment');
                            exit();
                        }
                    } else {
                        $_SESSION['join_errors'] = ['Event details not found.'];
                        redirect('receptionist/event_payment');
                        exit();
                    }
                } else {
                    $_SESSION['join_errors'] = ['Something went wrong while inserting data.'];
                    redirect('receptionist/event_payment');
                    exit();
                }
            } else {
                $_SESSION['join_errors'] = $eventModel->getErrors();
                $_SESSION['form_data'] = $data;
                redirect('receptionist/event_payment');
                exit();
            }
        }
    }


    public function settings()
    {
        $user_id = $_SESSION['user_id'];
        $receptionistModel = new M_Receptionist;
        $userModel = new M_User;
        $receptionist = $receptionistModel->findByReceptionistId($user_id);
        $user = $userModel->findByUserId($user_id);
        $data = [
            'receptionist' => $receptionist,
            'user' => $user
        ];
        $this->view('receptionist/receptionist-settings', $data);
    }

    public function updateSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $receptionistModel = new M_Receptionist;
            $userModel = new M_User;

            // Sanitize inputs
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Retrieve existing receptionist data to compare
            $receptionist_id = $_POST['user_id'];
            $existingReceptionist = $receptionistModel->findByReceptionistId($receptionist_id);

            // Retrieve existing user data to compare (for username check)
            $existingUser = $userModel->findByUserId($receptionist_id); // Assuming findByUserId exists for users table

            // Initialize data array to track changes
            $data = [];

            // Only include fields that have been updated
            $fields = ['first_name', 'last_name', 'NIC_no', 'date_of_birth', 'home_address', 'contact_number', 'email_address', 'image'];

            // Check for changes and add them to the data array
            foreach ($fields as $field) {
                if (isset($_POST[$field]) && $_POST[$field] !== $existingReceptionist->$field) {
                    $data[$field] = $_POST[$field];
                }
            }

            // Handle email uniqueness check manually if it's updated
            if (isset($_POST['email_address']) && $_POST['email_address'] !== $existingReceptionist->email_address) {
                if ($receptionistModel->emailExists($_POST['email_address'], $receptionist_id)) {
                    $_SESSION['error'] = "Email is already in use.";
                    $data = [
                        'errors' => ['email_address' => 'Email is already in use.'],
                        'receptionist' => $_POST
                    ];
                    $this->view('receptionist/receptionist-settings', $data);
                    return; // Prevent further execution if email is already in use
                }
            }

            // Check if the username has changed
            if (isset($_POST['username']) && $_POST['username'] !== $existingUser->username) {
                if ($userModel->usernameExists($_POST['username'])) {
                    $_SESSION['error'] = "Username is already taken.";
                    $data = [
                        'errors' => ['username' => 'Username is already in use.'],
                        'receptionist' => $_POST
                    ];
                    $this->view('receptionist/receptionist-settings', $data);
                    return; // Prevent further execution if username is already in use
                } else {
                    $data['username'] = $_POST['username'];
                }
            }

            // Handle file upload if exists and if changed
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "assets/images/Receptionist/";
                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                $targetFile = $targetDir . $fileName;

                // Validate the file (e.g., check file type and size) and move it to the target directory
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['image'] = $fileName; // Save the new filename for the database
                } else {
                    $errors['file'] = "Failed to upload the file. Please try again.";
                }
            }

            // Only proceed with the update if data exists
            if (!empty($data)) {


                // Update receptionist data with the updated values
                $updatedReceptionist = $receptionistModel->update($receptionist_id, $data, 'receptionist_id');

                // Update user data (if username was changed)
                if (isset($data['username'])) {
                    $updatedUser = $userModel->update($receptionist_id, ['username' => $data['username']], 'user_id');
                }

                // Check if the updates were successful
                if (!$updatedReceptionist && (isset($updatedUser) ? !$updatedUser : true)) {
                    $_SESSION['success'] = "Settings have been successfully updated!";
                } else {
                    $_SESSION['error'] = "No changes detected or update failed.";
                }

                redirect('receptionist/settings');
            } else {
                // If no changes, redirect back
                $_SESSION['error'] = "No changes were made.";
                redirect('receptionist/settings');
            }
        } else {
            redirect('receptionist/settings');
        }
    }
}
