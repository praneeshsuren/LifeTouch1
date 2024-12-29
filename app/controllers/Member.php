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
            // Get member ID from session
            $member_id = $_SESSION['member_id'] ?? null;
            if (!$member_id) {
                // If no member ID in session, redirect to login
                redirect('login');
            }

            // Get trainer ID from query string
            $trainer_id = $_GET['trainer_id'] ?? null;
            if (!$trainer_id) {
                // If no trainer ID is provided, redirect to trainer list
                redirect('member/member-viewtrainer');
                return;
            }
            $trainer_id = htmlspecialchars($trainer_id); // Sanitize the trainer_id

            $month = $_GET['month'] ?? date('m');
            $year = $_GET['year'] ?? date('Y');
            $month = (int)$month;
            $year = (int)$year;
            

            $bookingModel = new M_Booking();

            $trainerModel = new M_Trainer();
            $trainer = $trainerModel->where(['trainer_id' => $trainer_id], [], 1);
            if (!$trainer) {
                $errors[] = "Trainer not found.";
                redirect('member/member-viewtrainer');
                return;
            }
            $trainer_first_name = $trainer[0]->first_name;
            $trainer_id = $trainer[0]->trainer_id; 

            $memberModel = new M_Member(); 
            $member = $memberModel->findByMemberId($member_id);
            if (!$member) {
                redirect('login'); 
            }
            $member_first_name = $member->first_name;
            
            $timeslotModel = new M_Timeslot();
            $time_slots = $timeslotModel->findAll();

            // Fetch 
            $calendar = $bookingModel->build_calender($month, $year, $member_id, $trainer[0]->trainer_id);
            $time_slots = $timeslotModel->findAll();

            $this->view('member/member-trainerbooking', [
                'calendar' => $calendar,
                'time_slots' => $time_slots,
                'trainer' => $trainer[0],
                'trainer_id' => $trainer_id,
                'trainer_first_name' => $trainer_first_name,
                'member_first_name' => $member_first_name,
            ]);
        }

        public function memberTrainerbooking_create(){
            $errors =[];

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $member_id = $_SESSION['member_id'] ?? null;
                $trainer_id = $_POST['selectedTrainerId'] ?? null;
                $selected_date = $_POST['selectedDate'] ?? null;
                $selected_timeslot_id = $_POST['selectedTimeslotId'] ?? null;

                // Extract month and year from the selected date
                $date = new DateTime($selected_date);
                $month = $date->format('m');
                $year = $date->format('Y');
        
                $booking = new M_Booking;
                $data = [
                    'member_id' => $member_id,
                    'trainer_id' => $trainer_id,
                    'booking_date' => $selected_date,
                    'timeslot_id' => $selected_timeslot_id,
                    'status' => 'pending' // Set the status to pending initially
                ];
    
                if($booking->validate($data) && empty($errors)){
                    $booking->insert($data);
                    $trainerCalendarUrl ="/member/memberTrainerbooking?month=" . urlencode($month) . "&year=" . urlencode($year) . "&trainer_id=" . urlencode($trainer_id);
                    redirect($trainerCalendarUrl);
                } else {
                    // Merge validation errors with file upload errors
                    $errors = array_merge($errors, $booking->getErrors());
                }
            }
            $this->view('member/member-trainerbooking', ['errors' => $errors]);
        }
    }
