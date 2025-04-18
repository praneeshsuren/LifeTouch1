<?php

    class Member extends Controller{

        public function __construct() {
            // Check if the user is logged in as a member
            $this->checkAuth('member');
        }

        public function index(){
            $announcementModel = new M_Announcement;
        
            // Fetch the latest 4 announcements with admin names
            $announcements = $announcementModel->findAllWithAdminNames(4);
        
            $data = [
                'announcements' => $announcements
            ];
        
            $this->view('member/member-dashboard', $data);
        }

        public function Trainer($action = null){
            switch($action){
                case 'api':
                    $trainerModel = new M_Trainer;
                    $trainers = $trainerModel->findAll(); 
                    header('Content-Type: application/json');
                    echo json_encode($trainers);    
                    break;

                case 'viewTrainerapi':
                    $trainerModel = new M_Trainer;
                    $trainer = $trainerModel->findByTrainerId($_GET['id']);
                    header('Content-Type: application/json');
                    echo json_encode($trainer);
                    break;
                    
                case 'viewTrainer':
                    $this->view('member/member-viewtrainer');
                    break;
                        
                default:
                    $this->view('member/member-trainer');
                    break;
            }
        }

        public function Booking($action = null) {
            $member_id = $_SESSION['member_id'] ?? null;
            $trainer_id = $_GET['id'] ?? null;
            $month = (int)($_GET['month'] ?? date('m'));
            $year = (int)($_GET['year'] ?? date('Y'));
            
            $bookingModel = new M_Booking();
            
            if ($action === 'api') {
                $timeslotModel = new M_Timeslot();
                $timeSlots = $timeslotModel->findAll();
                $bookings = $bookingModel->getBookingsByMonthAndYear($member_id, $trainer_id, $month, $year);
        
                header('Content-Type: application/json');
                echo json_encode([
                    'bookings' => $bookings,
                    'timeSlots' => $timeSlots
                ]);
                exit;
            }

            $data = ['member_id' => $member_id];
            $this->view('member/member-booking', $data);
        }
        
        public function announcements(){

            $announcementModel = new M_Announcement;
            $announcements = $announcementModel->findAllWithAdminDetails();
            $data = [
                'announcements' => $announcements
            ];


            $this->view('member/member-announcements', $data);
            
        }

        public function Supplements(){
            $this->view('member/member-supplements');
        }

        public function Workoutschedules(){
            $this->view('member/member-workoutschedules');
        } 

        public function Payment(){
            $this->view('member/member-payment');
        } 

        public function settings(){
            
            $user_id = $_SESSION['user_id'];
            $memberModel = new M_Member;
            $userModel = new M_User;
            $user = $userModel->findByUserId($user_id);
            $member = $memberModel->findByMemberId($user_id);
            $data = [
                'member' => $member,
                'user' => $user
            ];

            $this->view('member/member-settings', $data);
        }

        public function updateSettings() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $memberModel = new M_Member;
                $userModel = new M_User;
        
                // Sanitize inputs
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
                // Retrieve existing member data to compare
                $member_id = $_POST['member_id'];
                $existingMember = $memberModel->findByMemberId($member_id);
        
                // Retrieve existing user data to compare (for username check)
                $existingUser = $userModel->findByUserId($member_id); // Assuming findByUserId exists for users table
        
                // Initialize data array to track changes
                $data = [];
        
                // Only include fields that have been updated
                $fields = ['first_name', 'last_name', 'NIC_no', 'date_of_birth', 'home_address', 'contact_number', 'email_address'];
        
                // Check for changes and add them to the data array
                foreach ($fields as $field) {
                    if (isset($_POST[$field]) && $_POST[$field] !== $existingMember->$field) {
                        $data[$field] = $_POST[$field];
                    }
                }
        
                // Handle email uniqueness check manually if it's updated
                if (isset($_POST['email_address']) && $_POST['email_address'] !== $existingMember->email_address) {
                    if ($memberModel->emailExists($_POST['email_address'], $member_id)) {
                        $_SESSION['error'] = "Email is already in use.";
                        $data = [
                            'errors' => ['email_address' => 'Email is already in use.'],
                            'member' => $_POST
                        ];
                        $this->view('member/member-settings', $data);
                        return; // Prevent further execution if email is already in use
                    }
                }
        
                // Check if the username has changed
                if (isset($_POST['username']) && $_POST['username'] !== $existingUser->username) {
                    if ($userModel->usernameExists($_POST['username'])) {
                        $_SESSION['error'] = "Username is already taken.";
                        $data = [
                            'errors' => ['username' => 'Username is already in use.'],
                            'member' => $_POST
                        ];
                        $this->view('member/member-settings', $data);
                        return; // Prevent further execution if username is already in use
                    } else {
                        $data['username'] = $_POST['username'];
                    }
                }
        
                // Handle profile picture upload
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
                    $fileTmp = $_FILES['profile_picture']['tmp_name'];
                    $fileName = basename($_FILES['profile_picture']['name']);
                    $targetPath = 'public/assets/images/Member/' . $fileName;
        
                    if (move_uploaded_file($fileTmp, $targetPath)) {
                        $data['image'] = $fileName;
                    } else {
                        $_SESSION['error'] = "Failed to upload profile picture.";
                        redirect('member/settings');
                        return;
                    }
                }
        
                // Only proceed with the update if data exists
                if (!empty($data)) {


                    // Update member data with the updated values
                    $updatedMember = $memberModel->update($member_id, $data, 'member_id');
        
                    // Update user data (if username was changed)
                    if (isset($data['username'])) {
                        $updatedUser = $userModel->update($member_id, ['username' => $data['username']], 'user_id');
                    }
        
                    // Check if the updates were successful
                    if (!$updatedMember && (isset($updatedUser) ? !$updatedUser : true)) {
                        $_SESSION['success'] = "Settings have been successfully updated!";
                    } else {
                        $_SESSION['error'] = "No changes detected or update failed.";
                    }
        
                    redirect('member/settings');
                } else {
                    // If no changes, redirect back
                    $_SESSION['error'] = "No changes were made.";
                    redirect('member/settings');
                }
            } else {
                redirect('member/settings');
            }
        }
}

?>