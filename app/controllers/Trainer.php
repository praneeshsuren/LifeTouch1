<?php

    class Trainer extends Controller{

        public function __construct() {
            // Check if the user is logged in as a trainer
            $this->checkAuth('trainer');
        }
        
        public function index(){
            $this->view('trainer/trainer-dashboard');
        }

        public function announcements(){
            $this->view('trainer/trainer-announcements');
        }

        public function members($action = null) {
            switch ($action) {
                case 'userDetails':
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
                    // Fetch member details using member ID from URL
                    $memberModel = new M_Member;
                    $member = $memberModel->findByMemberId($_GET['id']);
            
                    // Fetch workout schedules related to the member
                    $workoutScheduleModel = new M_WorkoutSchedule;
                    // Use the updated method to fetch all schedules for the member
                    $workoutSchedules = $workoutScheduleModel->findAllSchedulesByMemberId($_GET['id']);
                
                    // Prepare data for the view
                    $data = [
                        'member' => $member,
                        'workoutSchedules' => $workoutSchedules // Pass workout schedules
                    ];
            
                    // Load the view with member and workout schedule data
                    $this->view('trainer/trainer-memberWorkouts', $data);
                    break;
        
                case 'createWorkoutSchedule':
                    // Fetch member details if provided via URL
                    $memberModel = new M_Member;
                    $member = $memberModel->findByMemberId($_GET['id']);
            
                    // Pass member data to the create workout schedule view
                    $data = [
                        'member' => $member
                    ];
                    $this->view('trainer/trainer-createWorkoutSchedule', $data);
                    break;
        
                case 'api':
                    // Fetch all members and return as JSON
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll();
                
                    header('Content-Type: application/json');
                    echo json_encode($members);
                    exit;
                    break;
        
                default:
                    // If no action is specified, load the default view
                    $this->view('trainer/trainer-members');
                    break;
            }
        }        
        
        public function bookings($action = null) {
            $trainer_id = $_SESSION['trainer_id'] ?? null;
        
            $bookingModel = new M_Booking();
            $bookings = $bookingModel->bookingsForTrainer($trainer_id);
        
            if ($action === 'api') {
                header('Content-Type: application/json');
                echo json_encode($bookings);
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

    }