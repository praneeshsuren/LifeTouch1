<?php

    class Trainer extends Controller{

        public function __construct() {
            // Check if the user is logged in as a trainer
            $this->checkAuth('Trainer');
        }
        
        public function index(){
            $trainer_id = $_SESSION['user_id'];

            $announcementModel = new M_Announcement;
            $memberModel = new M_Member;
            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
            $bookingModel = new M_Booking;
        
            // Fetch the latest 4 announcements with admin names
            $announcements = $announcementModel->findAllWithAdminNames(4);
        
            // Fetch the All Count of members in the GYM
            $members = $memberModel->countAll();
            $recentMembers = $memberModel->countRecentMembers();
            $scheduleCount = $workoutScheduleDetailsModel->findCountByTrainerId($trainer_id);
            $bookings = $bookingModel->findCountByTrainerId($trainer_id);

            $data = [
                'announcements' => $announcements,
                'members' => $members,
                'recentMembers' => $recentMembers,
                'scheduleCount' => $scheduleCount,
                'bookings' => $bookings
            ];
        
            $this->view('trainer/trainer-dashboard', $data);
        }

        public function announcements(){

            $announcementModel = new M_Announcement;
            $announcements = $announcementModel->findAllWithAdminDetails();
            $data = [
                'announcements' => $announcements
            ];


            $this->view('trainer/trainer-announcements', $data);
            
        }

        public function members($action = null) {
            switch ($action) {
                case 'viewMember':
                    // Fetch member details using member ID from URL
                    $memberModel = new M_Member;
                    $member = $memberModel->findByMemberId($_GET['id']);
            
                    // Pass member data to view
                    $data = [
                        'member' => $member
                    ];
                    $this->view('trainer/trainer-viewMember', $data);
                    break;
        
                case 'workoutSchedules':
                    $memberId = $_GET['id'] ?? null;
                
                    if ($memberId) {
                        $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
                        $schedules = $workoutScheduleDetailsModel->findAllSchedulesByMemberId($memberId);
                
                        // Sort schedules in descending order by created_at
                        usort($schedules, function ($a, $b) {
                            return strtotime($b->created_at) - strtotime($a->created_at);
                        });
                
                        $data = [
                            'member_id' => $memberId,
                            'schedules' => $schedules
                        ];
                        $this->view('trainer/trainer-memberWorkouts', $data);
                    } else {
                        $_SESSION['error'] = 'Member not found.';
                        redirect('trainer/dashboard');
                    }
                    break;
                    
                
                case 'workoutDetails':
                    // Fetch the schedule ID from the URL
                    $scheduleId = $_GET['id'];
                
                    // Fetch the schedule details (weight, measurements, etc.)
                    $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
                    $schedule = $workoutScheduleDetailsModel->findByScheduleId($scheduleId);
                
                    // If no schedule is found, handle the error
                    if (!$schedule) {
                        $_SESSION['error'] = 'Workout schedule not found.';
                        redirect('trainer/members/workoutSchedules');
                        return;
                    }
                
                    // Fetch the workout rows (the individual workouts in the schedule)
                    $workoutScheduleModel = new M_WorkoutSchedule;
                    $workoutDetails = $workoutScheduleModel->findAllByScheduleId($scheduleId);
                
                    $data = [
                        'schedule' => $schedule,         // The schedule details (weight, measurements, etc.)
                        'workout_details' => $workoutDetails // The individual workout rows for the schedule
                    ];
                
                    // Render the view with the fetched data
                    $this->view('trainer/trainer-viewWorkoutSchedule', $data);
                    break;
        
                case 'createWorkoutSchedule':
                    $memberModel = new M_Member;
                    $member = $memberModel->findByMemberId($_GET['id']);
            
                    // Pass member data to the create workout schedule view
                    $data = [
                        'member' => $member
                    ];
                    $this->view('trainer/trainer-createWorkoutSchedule', $data);
                    break;

                case 'memberAttendance':
                    $this->view('trainer/trainer-memberAttendance');
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

                        $this->view('trainer/trainer-memberSupplements', $data);
                    } else {
                        $_SESSION['error'] = 'Member not found.';
                        redirect('trainer/members');
                    }
                    break;
        
                default:
                    // Fetch all members and pass to the view
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll('created_at');

                    $data = [
                        'members' => $members
                    ];

                    $this->view('trainer/trainer-members', $data);
                    break;
            }
        }        
        
        public function bookings($action = null) {
            $trainer_id = $_SESSION['user_id'] ?? null;
        
            $bookingModel = new M_Booking();
            $bookings = $bookingModel->bookingsForTrainer($trainer_id);
            $holidayModal = new M_Holiday();
            $holidays = $holidayModal->findAll();
        
            if ($action === 'api') {
                header('Content-Type: application/json');
                echo json_encode([
                    'bookings' =>$bookings,
                    'holidays' => $holidays
                ]);
                exit;
            } elseif($action === 'edit'){
                header('Content-type: application/json');

                $id = $_POST['id'] ?? null;
                $status = $_POST['status']?? null;

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
        
            $this->view('trainer/trainer-booking');
        }
        
        public function calendar(){
            $this->view('trainer/trainer-calendar');
        }

        public function workouts(){
            $this->view('trainer/trainer-workouts');
        }

        public function settings(){
            
            $user_id = $_SESSION['user_id'];
            $trainerModel = new M_Trainer;
            $userModel = new M_User;
            $user = $userModel->findByUserId($user_id);
            $trainer = $trainerModel->findByTrainerId($user_id);
            $data = [
                'trainer' => $trainer,
                'user' => $user
            ];

            $this->view('trainer/trainer-settings', $data);
        }

        public function updateSettings() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $trainerModel = new M_Trainer;
                $userModel = new M_User;
        
                // Sanitize inputs
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
                // Retrieve existing trainer data to compare
                $trainer_id = $_POST['user_id'];
                $existingTrainer = $trainerModel->findByTrainerId($trainer_id);
        
                // Retrieve existing user data to compare (for username check)
                $existingUser = $userModel->findByUserId($trainer_id); // Assuming findByUserId exists for users table
        
                // Initialize data array to track changes
                $data = [];
        
                // Only include fields that have been updated
                $fields = ['first_name', 'last_name', 'NIC_no', 'date_of_birth', 'home_address', 'contact_number', 'email_address', 'image'];
        
                // Check for changes and add them to the data array
                foreach ($fields as $field) {
                    if (isset($_POST[$field]) && $_POST[$field] !== $existingTrainer->$field) {
                        $data[$field] = $_POST[$field];
                    }
                }

                // Check for changes and add them to the data array
                foreach ($fields as $field) {
                    if (isset($_POST[$field]) && $_POST[$field] !== $existingTrainer->$field) {
                        $data[$field] = $_POST[$field];
                    }
                }
        
                // Handle email uniqueness check manually if it's updated
                if (isset($_POST['email_address']) && $_POST['email_address'] !== $existingTrainer->email_address) {
                    if ($trainerModel->emailExists($_POST['email_address'], $trainer_id)) {
                        $_SESSION['error'] = "Email is already in use.";
                        $data = [
                            'errors' => ['email_address' => 'Email is already in use.'],
                            'trainer' => $_POST
                        ];
                        $this->view('trainer/trainer-settings', $data);
                        return; // Prevent further execution if email is already in use
                    }
                }
        
                // Check if the username has changed
                if (isset($_POST['username']) && $_POST['username'] !== $existingUser->username) {
                    if ($userModel->usernameExists($_POST['username'])) {
                        $_SESSION['error'] = "Username is already taken.";
                        $data = [
                            'errors' => ['username' => 'Username is already in use.'],
                            'trainer' => $_POST
                        ];
                        $this->view('trainer/trainer-settings', $data);
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


                    // Update trainer data with the updated values
                    $updatedTrainer = $trainerModel->update($trainer_id, $data, 'trainer_id');
        
                    // Update user data (if username was changed)
                    if (isset($data['username'])) {
                        $updatedUser = $userModel->update($trainer_id, ['username' => $data['username']], 'user_id');
                    }
        
                    // Check if the updates were successful
                    if ($updatedTrainer && (isset($updatedUser) ? !$updatedUser : true)) {
                        $_SESSION['success'] = "Settings have been successfully updated!";
                    } else {
                        $_SESSION['error'] = "No changes detected or update failed.";
                    }
        
                    redirect('trainer/settings');
                } else {
                    // If no changes, redirect back
                    $_SESSION['error'] = "No changes were made.";
                    redirect('trainer/settings');
                }
            } else {
                redirect('trainer/settings');
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
            $this->view('trainer/trainer-notifications', $data);
        }

    }