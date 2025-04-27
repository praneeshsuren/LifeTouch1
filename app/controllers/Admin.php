<?php
    class Admin extends Controller{

        public function __construct() {
            // Check if the user is logged in as a admin
            $this->checkAuth('Admin');
        }

        public function index() {
            $announcementModel = new M_Announcement;
            $memberModel = new M_Member;
            $contactModel = new M_Contact;
            $eventParticipantsModel = new M_EventParticipants;
        
            // Fetch the latest 4 announcements with admin names
            $announcements = $announcementModel->findAllWithAdminNames(4);
        
            // Fetch the All Count of members in the GYM
            $members = $memberModel->countAll();
            $recentMembers = $memberModel->countRecentMembers();
            $recentContacts = $contactModel->countAllContactsInLast30Days();
            $eventParticipants = $eventParticipantsModel->countUniqueParticipants();

            $data = [
                'announcements' => $announcements,
                'members' => $members,
                'recentMembers' => $recentMembers,
                'recentContacts' => $recentContacts,
                'eventParticipants' => $eventParticipants
            ];
        
            $this->view('admin/admin-dashboard', $data);
        }
        

        public function events($action = null){
            switch ($action){
                case 'createEvent':

                    $this->view('admin/admin-createEvent');
                    break;
                
                case 'viewEvent':
                    // Load the view to view a trainer
                    $eventModel = new M_Event;
                    $event = $eventModel->findByEventId($_GET['id']);
        
                    $data = [
                        'event' => $event
                    ];
        
                    $this->view('admin/admin-viewEvent', $data);
                    break;
                
                default:

                    $eventModel = new M_Event;
                    $events = $eventModel->findAll('event_id');

                    $data = [
                        'events' => $events
                    ];

                    $this->view('admin/admin-events', $data);
                    break;
            }
        }

        public function inquiries($action = null){
            switch($action) {
                case 'api':
                    $inquiries_Model = new M_Contact();
                    $inquiries = $inquiries_Model->findAll();
                    header('Content-Type: application/json');
                    echo json_encode([
                        'inquiries' => $inquiries
                    ]);
                    break;
                case 'viewInquiryapi':
                    $inquiries_Model = new M_Contact();
                    $inquiry = $inquiries_Model->findInquityById($_GET['id']);   
                    header('Content-Type: application/json');
                    echo json_encode($inquiry); 
                    break;
                case 'viewInquiry' :
                    $inquiries_Model = new M_Contact();
                    $this->view('admin/admin-viewInquiry');
                    break;
                default:
                    $this->view('admin/admin-inquiries');
                    break;
            }

        }

        public function announcements($action = null){
            switch ($action){
                case 'createAnnouncement':

                    $this->view('admin/admin-createAnnouncement');
                    break;
                
                default:

                    $announcementModel = new M_Announcement;
                    $announcements = $announcementModel->findAllWithAdminDetails();

                    $data = [
                        'announcements' => $announcements
                    ];

                    $this->view('admin/admin-announcements', $data);
                    break;
            }
        }

        public function members($action = null) {
            switch ($action) {
                case 'createMember':
                    // Load the form view to create a member
                    $this->view('admin/admin-createMember');
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
        
                    $this->view('admin/admin-viewMember', $data);
                    break;
                
                case 'memberAttendance':
                    $this->view('admin/admin-memberAttendance');
                    break;

                case 'memberPaymentHistory':
                    $this->view('admin/admin-memberPaymentHistory');
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

                        $this->view('admin/admin-memberSupplements', $data);
                    } else {
                        $_SESSION['error'] = 'Member not found.';
                        redirect('admin/members');
                    }
                    break;
                
                default:
                    // Fetch all members and pass to the view
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll('created_at');

                    $data = [
                        'members' => $members
                    ];

                    $this->view('admin/admin-members', $data);
                    break;
            }
        }
        

        public function trainers($action = null) {
            switch ($action) {
                case 'createTrainer':
                    // Load the form view to create a trainer
                    $this->view('admin/admin-createTrainer');
                    break;
        
                
                case 'viewTrainer':
                    // Load the view to view a trainer
                    $trainerModel = new M_Trainer;
                    $trainer = $trainerModel->findByTrainerId($_GET['id']);
        
                    $data = [
                        'trainer' => $trainer
                    ];
        
                    $this->view('admin/admin-viewTrainer', $data);
                    break;
                
                case 'salaryHistory':

                    $this->view('admin/admin-trainerSalaryHistory');
                    break;

                case 'trainerCalendar':

                    $trainer_id = $_GET['id'];

                    $this->view('admin/admin-trainerCalendar',['trainer_id' => $trainer_id]);
                    break;
                
                default:
                    // Fetch all trainers and pass to the view
                    $trainerModel = new M_Trainer;
                    $trainers = $trainerModel->findAll('created_at');
        
                    $data = [
                        'trainers' => $trainers
                    ];
        
                    $this->view('admin/admin-trainers', $data);
                    break;
            }
        }

        public function receptionists($action = null) {
            switch ($action) {
                case 'createReceptionist':
                    // Load the form view to create a receptionist
                    $this->view('admin/admin-createReceptionist');
                    break;
        
                case 'viewReceptionist':
                    // Return specific receptionist data as JSON
                    $receptionistModel = new M_Receptionist;
                    $receptionist = $receptionistModel->findByReceptionistId($_GET['id']);

                    $data = [
                        'receptionist' => $receptionist
                    ];
                    $this->view('admin/admin-viewReceptionist', $data);
                    break;
                
                case 'salaryHistory':
                    $receptionist_id = $_GET['id'];

                    $this->view('admin/admin-receptionistSalaryHistory');
                    break;
        
                default:
                    // Fetch all receptionists and pass to the view
                    $receptionistModel = new M_Receptionist;
                    $receptionists = $receptionistModel->findAll('created_at');

                    $data = [
                        'receptionists' => $receptionists
                    ];

                    $this->view('admin/admin-receptionists', $data);
                    break;
            }
        }
        
        

        public function managers($action = null) {
            switch ($action) {
                case 'createManager':
                    // Load the form view to create a manager
                    $this->view('admin/admin-createManager');
                    break;
        

                case 'viewManager':
                    // Load the view to view a manager
                    $managerModel = new M_Manager;
                    $manager = $managerModel->findByManagerId($_GET['id']);
        
                    $data = [
                        'manager' => $manager
                    ];
        
                    $this->view('admin/admin-viewManager', $data);
                    break;            
                                       
        
                default:
                    // Fetch all managers and pass to the view
                    $managerModel = new M_Manager;
                    $managers = $managerModel->findAll('created_at');
        
                    $data = [
                        'managers' => $managers
                    ];
        
                    $this->view('admin/admin-managers', $data);
                    break;
            }
        }

        public function admins($action = null) {
            switch ($action) {
                case 'createAdmin':
                    // Load the form view to create a admin
                    $this->view('admin/admin-createAdmin');
                    break;

                case 'viewAdmin':
                    // Load the view to view a admin
                    $adminModel = new M_Admin;
                    $admin = $adminModel->findByAdminId($_GET['id']);
        
                    $data = [
                        'admin' => $admin
                    ];
        
                    $this->view('admin/admin-viewAdmin', $data);
                    break;
                    
                case 'salaryHistory':
                    
                    $admin_id = $_GET['id'];

                    $this->view('admin/admin-adminSalaryHistory');
                    break;
        
                default:
                    // Fetch all admins and pass to the view
                    $adminModel = new M_Admin;
                    $admins = $adminModel->findAll('created_at');

                    $data = [
                        'admins' => $admins
                    ];

                    $this->view('admin/admin-admins', $data);
                    break; 
            }
        }

        public function bookings($action = null){
            $bookingModel = new M_Booking();
            $bookings = $bookingModel->bookingsForAdmin();
            $holidayModal = new M_Holiday();
            $holidays = $holidayModal->findAll();
                
            if ($action === 'api'){
                header('Content-Type: application/json');
                echo json_encode([
                    'bookings' =>$bookings,
                    'holidays' => $holidays
                ]);
                exit;
            }
            $this->view('admin/admin-booking');
        }

        public function calendar(){
            $this->view('admin/admin-calendar');
        }


        public function payment($action = null){
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
            $this->view('admin/admin-payment');
        }

        public function settings(){
            
            $user_id = $_SESSION['user_id'];
            $adminModel = new M_Admin;
            $userModel = new M_User;
            $user = $userModel->findByUserId($user_id);
            $admin = $adminModel->findByAdminId($user_id);
            $data = [
                'admin' => $admin,
                'user' => $user
            ];

            $this->view('admin/admin-settings', $data);
        }

        public function updateSettings() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $adminModel = new M_Admin;
                $userModel = new M_User;
        
                // Sanitize inputs
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
                // Retrieve existing admin data to compare
                $admin_id = $_POST['user_id'];
                $existingAdmin = $adminModel->findByAdminId($admin_id);
        
                // Retrieve existing user data to compare (for username check)
                $existingUser = $userModel->findByUserId($admin_id); // Assuming findByUserId exists for users table
        
                // Initialize data array to track changes
                $data = [];
        
                // Only include fields that have been updated
                $fields = ['first_name', 'last_name', 'NIC_no', 'date_of_birth', 'home_address', 'contact_number', 'email_address', 'image'];
        
                // Check for changes and add them to the data array
                foreach ($fields as $field) {
                    if (isset($_POST[$field]) && $_POST[$field] !== $existingAdmin->$field) {
                        $data[$field] = $_POST[$field];
                    }
                }
        
                // Handle email uniqueness check manually if it's updated
                if (isset($_POST['email_address']) && $_POST['email_address'] !== $existingAdmin->email_address) {
                    if ($adminModel->emailExists($_POST['email_address'], $admin_id)) {
                        $_SESSION['error'] = "Email is already in use.";
                        $data = [
                            'errors' => ['email_address' => 'Email is already in use.'],
                            'admin' => $_POST
                        ];
                        $this->view('admin/admin-settings', $data);
                        return; // Prevent further execution if email is already in use
                    }
                }
        
                // Check if the username has changed
                if (isset($_POST['username']) && $_POST['username'] !== $existingUser->username) {
                    if ($userModel->usernameExists($_POST['username'])) {
                        $_SESSION['error'] = "Username is already taken.";
                        $data = [
                            'errors' => ['username' => 'Username is already in use.'],
                            'admin' => $_POST
                        ];
                        $this->view('admin/admin-settings', $data);
                        return; // Prevent further execution if username is already in use
                    } else {
                        $data['username'] = $_POST['username'];
                    }
                }
        
                // Handle file upload if exists and if changed
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/images/Admin/";
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


                    // Update admin data with the updated values
                    $updatedAdmin = $adminModel->update($admin_id, $data, 'admin_id');
        
                    // Update user data (if username was changed)
                    if (isset($data['username'])) {
                        $updatedUser = $userModel->update($admin_id, ['username' => $data['username']], 'user_id');
                    }
        
                    // Check if the updates were successful
                    if (!$updatedAdmin && (isset($updatedUser) ? !$updatedUser : true)) {
                        $_SESSION['success'] = "Settings have been successfully updated!";
                    } else {
                        $_SESSION['error'] = "No changes detected or update failed.";
                    }
        
                    redirect('admin/settings');
                } else {
                    // If no changes, redirect back
                    $_SESSION['error'] = "No changes were made.";
                    redirect('admin/settings');
                }
            } else {
                redirect('admin/settings');
            }
        }               
        
        public function notifications(){
            // Assuming the user ID is stored in session
            $userId = $_SESSION['user_id'];

            // Fetch notifications from the Notification model
            $notificationModel = new M_Notification();
            $notifications = $notificationModel->getNotifications($userId);

            // Pass notifications to the view
            $data['notifications'] = $notifications;

            // Load the notifications view
            $this->view('admin/admin-notifications', $data);
        }
    }

?>

