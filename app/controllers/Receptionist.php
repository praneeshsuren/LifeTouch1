<?php

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

            case 'salaryHistory':
                // Load the view to view a trainer's salary history
                $this->view('receptionist/receptionist-trainerSalaryHistory');
                break;
            
            case 'trainerCalendar':
                // Load the view to view a trainer's calendar
                $trainer_id = $_GET['id'];

                $this->view('receptionist/receptionist-trainerCalendar',['trainer_id' => $trainer_id]);
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
                $this->view('receptionist/receptionist-memberPaymentHistory');
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

    public function settings(){
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

    public function updateSettings() {
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

    public function notifications(){
        $receptionist_id = $_SESSION['user_id'];
        $notificationModel = new M_Notification;
        $notifications = $notificationModel->getNotifications($receptionist_id);

        $data = [
            'notifications' => $notifications
        ];

        $this->view('receptionist/receptionist-notifications', $data);
    }

}

?>
