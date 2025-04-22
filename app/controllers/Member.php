<?php

    class Member extends Controller{

        public function __construct() {
            // Check if the user is logged in as a member
            $this->checkAuth('member');
        }

        public function index($action = null) {
            $announcementModel = new M_Announcement;
            // Fetch the latest 4 announcements with admin names
            $announcements = $announcementModel->findAllWithAdminNames(4);
            $data = [
                'announcements' => $announcements
            ];
  
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
        
            $this->view('member/member-dashboard', $data);
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
        
        public function announcements(){

            $announcementModel = new M_Announcement;
            $announcements = $announcementModel->findAllWithAdminDetails();
            $data = [
                'announcements' => $announcements
            ];


            $this->view('member/member-announcements', $data);
            
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
            } else if ($action === 'savePayment'){
                if($_SERVER['REQUEST_METHOD'] === "POST") {
                    header('Content-Type: application/json');

                    $member_id = $_POST['member_id'] ?? null;
                    $plan_id = $_POST['plan_id'] ?? null;
                    $payment_intent_id = $_POST['payment_intent_id'] ?? null;
                    $status = $_POST['status'] ?? null;
                    $startDate = $_POST['startDate'] ?? null;
                    $endDate = $_POST['endDate'] ?? null;
                    $type = $_POST['paymentType'] ?? null;

                    $data = [
                        'member_id' => $member_id,
                        'plan_id' => $plan_id,
                        'payment_intent_id' => $payment_intent_id,
                        'status' => $status,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'type' => $type
                    ];
                    $result = $payment_Model->insert($data);
                    echo json_encode([
                        "success" => $result ? true : false, 
                        "message" => $result ? "Payment successful and saved!" : "Payment succeeded, but failed to save payment info"
                    ]);
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

        public function checkout(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $_SESSION['payment_data'] = [
                    'member_id' => $_POST['member_id'],
                    'id' => $_POST['package_id'],
                    'startDate' => $_POST['paymentStartDate'],
                    'endDate' => $_POST['paymentExpireDate'],
                    'type' => $_POST['payment_type']
                ];
        
                $this->view('member/member-cardPayment',[
                    'session' => $_SESSION['payment_data'],
                ]);
            } else {
                // Direct page access fallback
                header('Location: ' . URLROOT . '/member/membershipPlan');
                exit;
            }
        }

        public function cardPayment($action = null){
            $plan_Model = new M_Membership_plan();
            $plan = $plan_Model->findAll();
            if($action === 'api'){
                header('Content-type: application/json');
                $payment_data = isset($_SESSION['payment_data']) ? $_SESSION['payment_data'] : null;
                echo json_encode([
                    'plan' => $plan,
                    'session' => $payment_data
                ]);
                exit;
            }
        }

        public function membershipPlan($action = null){
            $member_id = $_SESSION['member_id'] ?? null;
            $plan_Model = new M_Membership_plan();
            $plan = $plan_Model->findAll();
            $subscription_Model = new M_Subscription();
            $subscription_Model->deactivateExpiredSubscriptions();
            $memberPlan = $subscription_Model->subscriptionMember($member_id);
            if($action === 'api'){
                header('Content-type: application/json');
                echo json_encode([
                    'plan' => $plan,
                    'subscription' => $memberPlan
                ]);
                exit;
            } 
            $data = ['member_id' => $member_id];
            $this->view('member/member-membership-plan',$data);
        }

        public function settings(){
            
            $user_id = $_SESSION['user_id'];
            $memberModel = new M_Member;
            $userModel = new M_User;
            $user = $userModel->findByUserId($user_id);
            $member = $memberModel->findByMemberId($user_id);
            $data = [
                'member' => $member,
                'user' => $user
            ];

            $this->view('member/member-settings', $data);
        }

        public function updateSettings() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $memberModel = new M_Member;
                $userModel = new M_User;
        
                // Sanitize inputs
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
                // Retrieve existing member data to compare
                $member_id = $_POST['member_id'];
                $existingMember = $memberModel->findByMemberId($member_id);
        
                // Retrieve existing user data to compare (for username check)
                $existingUser = $userModel->findByUserId($member_id); // Assuming findByUserId exists for users table
        
                // Initialize data array to track changes
                $data = [];
        
                // Only include fields that have been updated
                $fields = ['first_name', 'last_name', 'NIC_no', 'date_of_birth', 'home_address', 'contact_number', 'email_address'];
        
                // Check for changes and add them to the data array
                foreach ($fields as $field) {
                    if (isset($_POST[$field]) && $_POST[$field] !== $existingMember->$field) {
                        $data[$field] = $_POST[$field];
                    }
                }
        
                // Handle email uniqueness check manually if it's updated
                if (isset($_POST['email_address']) && $_POST['email_address'] !== $existingMember->email_address) {
                    if ($memberModel->emailExists($_POST['email_address'], $member_id)) {
                        $_SESSION['error'] = "Email is already in use.";
                        $data = [
                            'errors' => ['email_address' => 'Email is already in use.'],
                            'member' => $_POST
                        ];
                        $this->view('member/member-settings', $data);
                        return; // Prevent further execution if email is already in use
                    }
                }
        
                // Check if the username has changed
                if (isset($_POST['username']) && $_POST['username'] !== $existingUser->username) {
                    if ($userModel->usernameExists($_POST['username'])) {
                        $_SESSION['error'] = "Username is already taken.";
                        $data = [
                            'errors' => ['username' => 'Username is already in use.'],
                            'member' => $_POST
                        ];
                        $this->view('member/member-settings', $data);
                        return; // Prevent further execution if username is already in use
                    } else {
                        $data['username'] = $_POST['username'];
                    }
                }
        
                // Handle profile picture upload
                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
                    $fileTmp = $_FILES['profile_picture']['tmp_name'];
                    $fileName = basename($_FILES['profile_picture']['name']);
                    $targetPath = 'public/assets/images/Member/' . $fileName;
        
                    if (move_uploaded_file($fileTmp, $targetPath)) {
                        $data['image'] = $fileName;
                    } else {
                        $_SESSION['error'] = "Failed to upload profile picture.";
                        redirect('member/settings');
                        return;
                    }
                }
        
                // Only proceed with the update if data exists
                if (!empty($data)) {


                    // Update member data with the updated values
                    $updatedMember = $memberModel->update($member_id, $data, 'member_id');
        
                    // Update user data (if username was changed)
                    if (isset($data['username'])) {
                        $updatedUser = $userModel->update($member_id, ['username' => $data['username']], 'user_id');
                    }
        
                    // Check if the updates were successful
                    if (!$updatedMember && (isset($updatedUser) ? !$updatedUser : true)) {
                        $_SESSION['success'] = "Settings have been successfully updated!";
                    } else {
                        $_SESSION['error'] = "No changes detected or update failed.";
                    }
        
                    redirect('member/settings');
                } else {
                    // If no changes, redirect back
                    $_SESSION['error'] = "No changes were made.";
                    redirect('member/settings');
                }
            } else {
                redirect('member/settings');
            }
        }
}

?>
