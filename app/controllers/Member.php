<?php

    class Member extends Controller{

        public function __construct() {
            // Check if the user is logged in as a member
            $this->checkAuth('member');
        }
        
        public function index(){
            $this->view(('member/member-dashboard'));
        }
        public function memberViewtrainer(){
            $trainerModel = new M_Trainer();
            $data['trainer'] = $trainerModel->findAll();
            $this->view('member/member-viewtrainer',$data);
        }
        public function memberViewtrainerViewbtn($trainerId1, $trainerId2, $trainerId3){
            // Combine the trainer ID parts into a single string
            $trainerId = $trainerId1 . '/' . $trainerId2 . '/' . $trainerId3;
        
            $trainerModel = new M_Trainer();
            // Fetch the trainer by the combined ID
            $trainer = $trainerModel->where(['trainer_id' => $trainerId], [], 1);
        
            if (!$trainer) {
                echo "Trainer not found with ID: $trainerId";
                redirect('member/member-viewtrainer');
                return;
            }
        
            // Pass the trainer data to the view
            $this->view('member/member-viewtrainer-viewbtn', ['trainer' => $trainer[0]]);
        }
        
        public function memberAnnouncements(){
            $this->view('member/member-announcements');
        }
        public function memberSupplements(){
            $this->view('member/member-supplements');
        }
        public function memberWorkoutschedules(){
            $this->view('member/member-workoutschedules');
        } 
        public function memberPayment(){
            $this->view('member/member-payment');
        } 
        public function memberSettings(){
            $this->view('member/member-settings');
        }
        public function memberTrainerbooking(){
            $errors = [];

            $month = $_GET['month'] ?? date('m');
            $year = $_GET['year'] ?? date('Y');

            // Sanitize month and year to avoid invalid values
            $month = (int)$month;
            $year = (int)$year;
    
            $bookingModel = new M_Booking();
            $timeslotModel = new M_Timeslot();

            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $data = [
                    'booking_date' => $_POST['selectedDate'] ?? null,
                    'time_slot' => $_POST['selectedTimeslot'] ?? null,
                    'status' => 'pending',
                ];

                if ($bookingModel->validate($data) && empty($errors)) {
                    $bookingModel->insert($data);

                    header('Location: memberTrainerbooking');
                    exit();
                } else {
                    $errors = array_merge($errors, $bookingModel->getErrors());
                }

                header("Location: " . URLROOT . "/member/memberTrainerbooking?month=$month&year=$year");
                exit;

            }

            // Fetch 
            $calendar = $bookingModel->build_calender($month, $year);
            $time_slots = $timeslotModel->findAll();

            $this->view('member/member-trainerbooking', [
                'calendar' => $calendar,
                'time_slots' => $time_slots,
                'errors' => $errors ?? null,
            ]);
        }

    }