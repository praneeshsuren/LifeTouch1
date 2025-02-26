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

                case 'viewMember':
                    // Load the view to view a trainer
                    $memberModel = new M_Member;
                    $member = $memberModel->findByMemberId($_GET['id']);
        
                    $data = [
                        'member' => $member
                    ];
        
                    $this->view('trainer/trainer-viewMember', $data);
                    break;
                
                case 'workoutSchedules':
        
                    $this->view('trainer/trainer-memberWorkouts');
                    break;

                case 'createWorkoutSchedule':
                    $this->view('trainer/trainer-createWorkoutSchedule');
                    break;    
                
                case 'api':
                    // Load the view to view a trainer
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll();
        
                    header('Content-Type: application/json');
                    echo json_encode($members);
                    exit;
                    break;

                default:

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