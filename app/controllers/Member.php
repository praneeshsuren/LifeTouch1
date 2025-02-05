<?php

    class Member extends Controller{
        public function __construct() {
            // Check if the user is logged in as a member
            $this->checkAuth('member');
        }
        public function index(){
            $this->view(('member/member-dashboard'));
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
        public function Announcements(){
            $this->view('member/member-announcements');
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
        public function Settings(){
            $this->view('member/member-settings');
        }   
}