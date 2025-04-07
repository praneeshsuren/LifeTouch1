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
            } elseif($action === 'add'){
                if($_SERVER['REQUEST_METHOD'] == 'POST'){
                    header('Content-Type: application/json');
                    $date = $_POST['date']?? null;
                    $timeslotId = $_POST['timeslotId'] ?? null;
                    $member_id = $_POST['memberId'] ?? null;
                    $trainer_id = $_POST['trainerId'] ?? null;

                    $data = [
                        'booking_date' => $date,
                        'trainer_id' => $trainer_id,
                        'member_id' => $member_id,
                        'timeslot_id' => $timeslotId
                    ];

                    $result = $bookingModel->insert($data);
                    echo json_encode([
                        "success" => $result ? true : false, 
                        "message" => $result ? "bokking added successfully!" : "Failed to add booking"
                    ]);
                    exit;

                }
            } elseif($action === 'delete'){
                if($_SERVER['REQUEST_METHOD'] === "POST"){
                    $id = $_POST['id'];

                    if ($bookingModel->delete($id)) {
                        echo json_encode(["success" => true, "message" => "Booking deleted successfully!"]);
                        exit;
                    } else {
                        echo json_encode(["success" => false, "message" => "Error deleting Booking."]);
                        exit;
                    }
                }
                echo json_encode(["success" => false, "message" => "Invalid request."]);
                exit;
            } elseif($action === 'edit'){
                header('Content-type: application/json');

                $id = $_POST['id'] ?? null;
                $timeslot_id =$_POST['timeslot_id'] ?? null;

                if (!$id && !$timeslot_id) {
                    echo json_encode(["success" => false, "message" => "Missing required fields"]);
                    exit;
                }

                $data = [ 'timeslot_id' => $timeslot_id ];

                $result = $bookingModel->update($id, $data);

                echo json_encode(
                    [
                        "success" => $result ? true : false,
                        "message" => $result ? "Booking timeslot updated successfully!" : "Failed to update timeslot"
                    ]
                    );
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