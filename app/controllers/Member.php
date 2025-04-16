<?php

    class Member extends Controller{
        public function __construct() {
            // Check if the user is logged in as a member
            $this->checkAuth('member');
        }
        public function index($action = null){
            $member_id = $_SESSION['member_id'] ?? null;
            $bookingModel = new M_Booking();
            $bookings = $bookingModel->bookingsForMember($member_id);
            if ($action === 'api') {
                header('Content-Type: application/json');
                echo json_encode([
                    'bookings' => $bookings
                ]);
                exit;
            }
            $this->view('member/member-dashboard');
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
                $isBooked = $bookingModel->isBooked($trainer_id);
                $holidayModal = new M_Holiday();
                $holidays = $holidayModal->findAll();
        
                header('Content-Type: application/json');
                echo json_encode([
                    'bookings' => $bookings,
                    'timeSlots' => $timeSlots,
                    'holidays' => $holidays,
                    'isBooked' => $isBooked
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

        public function Payment($action = null) {
            $member_id = $_SESSION['member_id'] ?? null;
            $payment_Model = new M_Payment();
            $payment = $payment_Model->paymentMember($member_id);

            if ($action === 'api') {
                header('Content-Type: application/json');
                echo json_encode([
                    'payment' => $payment
                ]);
                exit;
            } elseif($action === 'add'){
                if($_SERVER['REQUEST_METHOD'] === 'POST'){
                    header('Content-Type: application/json');

                    $date = $_POST['paymentDate'] ?? null;
                    $member_id = $_POST['member_id'] ?? null;
                    $package = $_POST['packageName'] ?? null;
                    $email = $_POST['email'] ?? null;
                    $amount = $_POST['amount'] ?? null;
                    $payment_intent_id = $_POST['payment_intent_id'] ?? null;
                    $status = $_POST['status'] ?? null;

                    $data = [
                        'email' => $email,
                        'package_name' => $package,
                        'amount' => $amount,
                        'member_id' => $member_id,
                        'created_at' => $date,
                        'payment_intent_id' => $payment_intent_id,
                        'status' => $status,
                    ];
                    
                    $result = $payment_Model->insert($data);
                    echo json_encode(['success' => $result]);
                    exit;

                }
            }
            $data = ['member_id' => $member_id];
            $this->view('member/member-payment',$data); 
        }
        
        public function createPayment(){
            \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

             // Read the incoming JSON from the frontend
            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            $amount = isset($jsonObj->amount) ? intval($jsonObj->amount) * 100 : 300000;
            $currency = 'lkr';

            try {
                // Create a Payment Intent using Stripe's API
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => $currency,
                    'payment_method_types' => ['card'],
                ]);

                // Respond with the client secret for the frontend to handle
                echo json_encode([
                    'clientSecret' => $paymentIntent->client_secret,
                ]);
            } catch (\Stripe\Exception\ApiErrorException $e) {
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
        } 

        public function Settings(){
            $this->view('member/member-settings');
        }   
}