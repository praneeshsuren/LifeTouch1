<?php

    class Member extends Controller{

        public function __construct() {
            // Check if the user is logged in as a member
            $this->checkAuth('Member');
        }

        public function index($action = null) {
            $member_id = $_SESSION['user_id'];

            $announcementModel = new M_Announcement;
            $announcements = $announcementModel->findAllWithAdminNames(5);

            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
            $completedSchedules = count($workoutScheduleDetailsModel->findAllCompletedSchedulesByMemberId($member_id));

            $supplementSalesModel = new M_SupplementSales;
            $supplementsPurchased = count($supplementSalesModel->findSupplementsPurchased($member_id));

            $bookingModel = new M_Booking();
            $bookings = $bookingModel->bookingsForMember($member_id);
        
            if ($action === 'api') {
                header('Content-Type: application/json');
                echo json_encode([
                    'bookings' => $bookings
                ]);
                exit;
            }
            $data = [
                'announcements' => $announcements,
                'completedSchedules' => $completedSchedules,
                'supplementsPurchased' => $supplementsPurchased,
            ];

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
            $member_id = $_SESSION['user_id'] ?? null;
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

                    $message = $result ? "Booking added successfully!" : "Failed to add booking";
                    
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

        public function supplements(){
            $member_id = $_SESSION['user_id'];

            $supplementSalesModel = new M_SupplementSales;

            $supplementRecords = $supplementSalesModel->findByMemberId($member_id);
    
            $data = [
                'supplements' => $supplementRecords
            ];

            $this->view('member/member-supplements', $data);
        }

        public function workoutSchedules(){
            $member_id = $_SESSION['user_id'];
            
            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
            $schedules = $workoutScheduleDetailsModel->findAllSchedulesByMemberId($member_id);
    
            usort($schedules, function ($a, $b) {
                return strtotime($b->created_at) - strtotime($a->created_at);
            });
    
            $data = [
                'member_id' => $member_id,
                'schedules' => $schedules
            ];

            $this->view('member/member-workoutSchedules', $data);
        }

        public function workoutSchedulesApi(){
            $member_id = $_SESSION['user_id'];
            
            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
            $schedules = $workoutScheduleDetailsModel->findAllSchedulesByMemberId($member_id);
    
            usort($schedules, function ($a, $b) {
                return strtotime($a->created_at) - strtotime($b->created_at);
            });
    
            header('Content-Type: application/json');
            echo json_encode($schedules);
        }

        public function workoutDetails(){

            $schedule_id = $_GET['id'] ?? null;

            // Fetch the schedule details (weight, measurements, etc.)
            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
            $schedule = $workoutScheduleDetailsModel->findByScheduleId($schedule_id);
        
            // If no schedule is found, handle the error
            if (!$schedule) {
                $_SESSION['error'] = 'Workout schedule not found.';
                redirect('trainer/members/workoutSchedules');
                return;
            }
        
            // Fetch the workout rows (the individual workouts in the schedule)
            $workoutScheduleModel = new M_WorkoutSchedule;
            $workoutDetails = $workoutScheduleModel->findAllByScheduleId($schedule_id);
        
            $data = [
                'schedule' => $schedule,         // The schedule details (weight, measurements, etc.)
                'workout_details' => $workoutDetails // The individual workout rows for the schedule
            ];

            $this->view('member/member-workoutDetails', $data);
        }

        public function Payment($action = null) {
            $member_id = $_SESSION['user_id'] ?? null;
            $payment_Model = new M_Payment();
            $payment = $payment_Model->paymentMember($member_id);
            $plan_Model = new M_Membership_plan();
            $plan = $plan_Model->findAll();

            $subscription_Model = new M_Subscription();
            $subscription_Model->deactivateExpiredSubscriptions();
            $memberPlan = $subscription_Model->subscriptionMember($member_id);

            if($action === 'api'){
                header('Content-type: application/json');
                echo json_encode([
                    'payment' => $payment,
                    'plan' => $plan,
                    'subscription' => $memberPlan
                ]);
                exit;
            } elseif ($action === 'savePayment'){
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

                    $message = $result ? "Payment successful and saved!" : "Payment succeeded, but failed to save payment info";
                    
                    echo json_encode([
                        "success" => $result ? true : false, 
                        "message" => $result ? "Payment successful and saved!" : "Payment succeeded, but failed to save payment info"
                    ]);
                    exit;
                }
            }
            $this->view('member/member-payment');
        }
        
        public function createPayment(){
            \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

            $jsonStr = file_get_contents('php://input');
            $jsonObj = json_decode($jsonStr);

            $amount = isset($jsonObj->amount) ? intval($jsonObj->amount) * 100 : 300000;
            $currency = 'lkr';

            try {
                $paymentIntent = \Stripe\PaymentIntent::create([
                    'amount' => $amount,
                    'currency' => $currency,
                    'payment_method_types' => ['card'],
                ]);

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
            $member_id = $_SESSION['user_id'] ?? null;

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
                $member_id = $_POST['user_id'];
                $existingMember = $memberModel->findByMemberId($member_id);
        
                // Retrieve existing user data to compare (for username check)
                $existingUser = $userModel->findByUserId($member_id); // Assuming findByUserId exists for users table
        
                // Initialize data array to track changes
                $data = [];
        
                // Only include fields that have been updated
                $fields = ['first_name', 'last_name', 'NIC_no', 'date_of_birth', 'home_address', 'contact_number', 'email_address', 'image'];
        
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
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "assets/images/Member/";
                    $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                    $targetFile = $targetDir . $fileName;
        
                    // Validate the file (e.g., check file type and size) and move it to the target directory
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                        $data['image'] = $fileName; // Save the new filename for the database
                    } else {
                        $errors['file'] = "Failed to upload the file. Please try again.";
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

        public function notifications(){
            $member_id = $_SESSION['user_id'];
            $notificationModel = new M_Notification;
            $notifications = $notificationModel->getNotifications($member_id);
    
            $data = [
                'notifications' => $notifications
            ];

            $this->view('member/member-notifications', $data);
        }
}

?>
