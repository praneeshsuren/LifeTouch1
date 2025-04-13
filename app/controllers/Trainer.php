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
                    $this->view('trainer/trainer-memberWorkouts');
                    break;
                
                case 'workoutDetails':
                    // Fetch specific workout details using workout ID from URL
                    $scheduleId = $_GET['id']; // Get workout schedule ID from URL
                    
                    // Fetch workout schedule details
                    $workoutScheduleModel = new M_WorkoutSchedule;
                    $schedule = $workoutScheduleModel->findByScheduleId($scheduleId);
    
                    if (!$schedule) {
                        // Handle case where schedule ID is not found
                        $this->view('404'); // Optionally, redirect to a 404 page
                        return;
                    }
    
                    // Pass schedule data to the view
                    $data = [
                        'schedule' => $schedule
                    ];
                    $this->view('trainer/trainer-viewWorkoutSchedule', $data);
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