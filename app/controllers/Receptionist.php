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

        // Fetch the latest 4 announcements with admin names
        $announcements = $announcementModel->findAllWithAdminNames(4);

        $data = [
            'announcements' => $announcements
        ];

        $this->view('receptionist/receptionist-dashboard', $data);
    }

    public function announcements(){

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


            case 'userDetails':
                // Load the view to view a trainer
                $memberModel = new M_Member;
                $member = $memberModel->findByMemberId($_GET['id']);

                $data = [
                    'member' => $member
                ];

                $this->view('receptionist/receptionist-viewMember', $data);
                break;


            case 'supplementRecords':
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

                case 'memberPaymentHistory':
                    $this->view('receptionist/payment_history');
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

    public function payment($action = null)
    {
        $paymentModel = new M_Payment();
        $payment = $paymentModel->paymentAdmin();
        $plan_Model = new M_Membership_plan();
        $plan = $plan_Model->findAll();

        if($action === 'api'){
            header('Content-Type: application/json');
            echo json_encode([
                'payment' => $payment,
                'plan' => $plan
            ]);
            exit;
        }
        $this->view('receptionist/receptionist-payment');
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
        // Instantiate the model
        $eventModel = new M_JoinEvent();
        $eventDetailsModel = new M_Event(); // Instantiate the M_Event model

        // Collect form data
        $data = [
            'event_id' => $_POST['event_id'],
            'full_name' => $_POST['full_name'],
            'nic' => $_POST['nic'],
            'email' => $_POST['email'],
            'is_member' => $_POST['is_member'],
            'membership_number' => $_POST['membership_number'] ?? '', // If member, use membership number; otherwise, set it as empty.
        ];

        // Validate the data
        if ($eventModel->validate($data)) {
            // If validation passes, create the participant
            if ($eventModel->insert($data)) {
                
                // Fetch event details
                $eventDetails = $eventDetailsModel->getEventById($data['event_id']); // Fetch event details based on event_id
                if ($eventDetails) {
                    // Prepare event details for email
                    $eventName = $eventDetails->name;
                    $eventDate = date('F j, Y', strtotime($eventDetails->event_date));
                    $startTime = date('g:i A', strtotime($eventDetails->start_time));
                    $eventLocation = $eventDetails->location;

                    // Send email confirmation
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'amandanethmini100@gmail.com'; // Your Gmail
                        $mail->Password = 'niib zlpx xskb bmag'; // App password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Recipients
                        $mail->setFrom('amandanethmini100@gmail.com', 'Life Touch Fitness');
                        $mail->addAddress($data['email'], $data['full_name']);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Confirmation: Event Registration';
                        $mail->Body    = "
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

                        // Send the email
                        $mail->send();
                        
                        // Redirect after email is sent
                        redirect('receptionist/event_payment');
                        exit();
                    } catch (Exception $e) {
                        // Handle email error
                        $_SESSION['join_errors'] = ['email' => 'Mailer Error: ' . $mail->ErrorInfo];
                        $_SESSION['form_data'] = $data;
                        redirect('receptionist/event_payment');
                        exit();
                    }
                } else {
                    // Handle case where event details are not found
                    $_SESSION['join_errors'] = ['event' => 'Event details not found.'];
                    redirect('receptionist/event_payment');
                    exit();
                }
            } else {
                // Handle failure (e.g., show error message)
                die('Error inserting participant');
            }
        } else {
            // If validation failed, show errors
            $errors = $eventModel->getErrors();
            // You can load the view with the error messages
        }
    }
}


}
