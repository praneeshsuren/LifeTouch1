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
                    $trainerModel = new M_Trainer();
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
        // public function memberViewtrainerViewbtn($trainerId1, $trainerId2, $trainerId3){
        //     // Combine the trainer ID parts into a single string
        //     $trainerId = $trainerId1 . '/' . $trainerId2 . '/' . $trainerId3;
        
        //     $trainerModel = new M_Trainer();
        //     // Fetch the trainer by the combined ID
        //     $trainer = $trainerModel->where(['trainer_id' => $trainerId], [], 1);
        
        //     if (!$trainer) {
        //         echo "Trainer not found with ID: $trainerId";
        //         redirect('member/member-viewtrainer');
        //         return;
        //     }
        
        //     // Pass the trainer data to the view
        //     $this->view('member/member-viewtrainer-viewbtn', ['trainer' => $trainer[0]]);
        // }
        
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
        public function memberTrainerbooking() {
            $errors = [];
        
            // Validate session member_id
            $member_id = $_SESSION['member_id'] ?? null;
            
            // Validate trainer_id
            $trainer_id = $_GET['trainer_id'] ?? null;
            $trainer_id = htmlspecialchars($trainer_id);
           
            $month = (int)($_GET['month'] ?? date('m'));
            $year = (int)($_GET['year'] ?? date('Y'));
        
            $bookingModel = new M_Booking();
            $timeslotModel = new M_Timeslot();
         
            // Fetch time slots
            $time_slots = $timeslotModel->findAll();
        
            // Build calendar
            $calendar = $bookingModel->build_calender($month, $year, $member_id, $trainer_id);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $selectedTrainerId = $_POST['selectedTrainerId'] ?? null;
                $selectedDate = $_POST['selectedDate'] ?? null;
                $selectedTimeslotId = $_POST['selectedTimeslotId'] ?? null;
        
                $data = [
                    'member_id' => $member_id,
                    'trainer_id' => $selectedTrainerId,
                    'booking_date' => $selectedDate,
                    'timeslot_id' => $selectedTimeslotId,
                    'status' => 'pending',
                ];
        
                $booking = new M_Booking();
                if ($booking->validate($data) && empty($errors)) {
                    $booking->insert($data);
                    header('Location: ' . URLROOT . '/member/memberTrainerbooking?trainer_id=' . urlencode($selectedTrainerId));
                } else {
                    $errors = array_merge($errors, $booking->getErrors());
                }
            }
        
            $this->view('member/member-trainerbooking', [
                'calendar' => $calendar,
                'time_slots' => $time_slots,
                'trainer_id' => $trainer_id,
                'member_id' => $member_id,
                'errors' => $errors,
            ]);
        }
        
    }
