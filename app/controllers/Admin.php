<?php
    class Admin extends Controller{

        public function __construct() {
            // Check if the user is logged in as a admin
            $this->checkAuth('admin');
        }

        public function index(){
            
            $announcementModel = new M_Announcement;
            $announcements = $announcementModel->findAll('announcement_id', 4);

            $data = [
                'announcements' => $announcements
            ];

            $this->view('admin/admin-dashboard', $data);
            
        }

        public function events($action = null){
            switch ($action){
                case 'createEvent':

                    $this->view('admin/admin-createEvent');
                    break;
                
                case 'viewEvent':
                    // Load the view to view a trainer
                    $eventModel = new M_Event;
                    $event = $eventModel->findByEventId($_GET['id']);
        
                    $data = [
                        'event' => $event
                    ];
        
                    $this->view('admin/admin-viewEvent', $data);
                    break;
                
                default:

                    $eventModel = new M_Event;
                    $events = $eventModel->findAll('event_id');

                    $data = [
                        'events' => $events
                    ];

                    $this->view('admin/admin-events', $data);
                    break;
            }
        }

        public function inquiries(){
            
            $this->view('admin/admin-inquiries');

        }

        public function announcements($action = null){
            switch ($action){
                case 'createAnnouncement':

                    $this->view('admin/admin-createAnnouncement');
                    break;
                
                default:

                    $announcementModel = new M_Announcement;
                    $announcements = $announcementModel->findAll('announcement_id');

                    $data = [
                        'announcements' => $announcements
                    ];

                    $this->view('admin/admin-announcements', $data);
                    break;
            }
        }

        public function members($action = null) {
            switch ($action) {
                case 'createMember':
                    // Load the form view to create a member
                    $this->view('admin/admin-createMember');
                    break;
        

                case 'viewMember':
                    // Load the view to view a trainer
                    $memberModel = new M_Member;
                    $member = $memberModel->findByMemberId($_GET['id']);
        
                    $data = [
                        'member' => $member
                    ];
        
                    $this->view('admin/admin-viewMember', $data);
                    break;
                
                
                default:
                    // Fetch all members and pass to the view
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll('created_at');

                    $data = [
                        'members' => $members
                    ];

                    $this->view('admin/admin-members', $data);
                    break;
            }
        }

        public function trainers($action = null) {
            switch ($action) {
                case 'createTrainer':
                    // Load the form view to create a trainer
                    $this->view('admin/admin-createTrainer');
                    break;
        
                
                case 'viewTrainer':
                    // Load the view to view a trainer
                    $trainerModel = new M_Trainer;
                    $trainer = $trainerModel->findByTrainerId($_GET['id']);
        
                    $data = [
                        'trainer' => $trainer
                    ];
        
                    $this->view('admin/admin-viewTrainer', $data);
                    break;                             
        
                default:
                    // Fetch all trainers and pass to the view
                    $trainerModel = new M_Trainer;
                    $trainers = $trainerModel->findAll('created_at');
        
                    $data = [
                        'trainers' => $trainers
                    ];
        
                    $this->view('admin/admin-trainers', $data);
                    break;
            }
        }

        public function receptionists($action = null) {
            switch ($action) {
                case 'createReceptionist':
                    // Load the form view to create a receptionist
                    $this->view('admin/admin-createReceptionist');
                    break;
        
                case 'viewReceptionist':
                    // Return specific receptionist data as JSON
                    $receptionistModel = new M_Receptionist;
                    $receptionist = $receptionistModel->findByReceptionistId($_GET['id']);
                    header('Content-Type: application/json');
                    $this->view('admin/admin-viewReceptionist');
                    break;
        
                default:
                    $this->view('admin/admin-receptionists');
                    break;

                case 'api':
                    // Return all receptionists data as JSON
                    $receptionistModel = new M_Receptionist;
                    $receptionists = $receptionistModel->findAll('receptionist_id');
                    header('Content-Type: application/json');
                    echo json_encode($receptionists);
                    break;
            }
        }
        
        

        public function managers($action = null) {
            switch ($action) {
                case 'createManager':
                    // Load the form view to create a manager
                    $this->view('admin/admin-createManager');
                    break;
        

                case 'viewManager':
                    // Load the view to view a manager
                    $managerModel = new M_Manager;
                    $manager = $managerModel->findByManagerId($_GET['id']);
        
                    $data = [
                        'manager' => $manager
                    ];
        
                    $this->view('admin/admin-viewManager', $data);
                    break;            
                                       
        
                default:
                    // Fetch all managers and pass to the view
                    $managerModel = new M_Manager;
                    $managers = $managerModel->findAll('created_at');
        
                    $data = [
                        'managers' => $managers
                    ];
        
                    $this->view('admin/admin-managers', $data);
                    break;
            }
        }

        public function admins($action = null) {
            switch ($action) {
                case 'createAdmin':
                    // Load the form view to create a admin
                    $this->view('admin/admin-createAdmin');
                    break;

                case 'viewAdmin':
                    // Load the view to view a admin
                    $adminModel = new M_Admin;
                    $admin = $adminModel->findByAdminId($_GET['id']);
        
                    $data = [
                        'admin' => $admin
                    ];
        
                    $this->view('admin/admin-viewAdmin', $data);
                    break;                               
        
                default:
                    // Fetch all admins and pass to the view
                    $adminModel = new M_Admin;
                    $admins = $adminModel->findAll('created_at');
        
                    $data = [
                        'admins' => $admins
                    ];
        
                    $this->view('admin/admin-admins', $data);
                    break;
            }
        }

        public function bookings($action = null){
            $bookingModel = new M_Booking();
            $bookings = $bookingModel->bookingsForAdmin();
            $holidayModal = new M_Holiday();
            $holidays = $holidayModal->findAll();
                
            if ($action === 'api'){
                header('Content-Type: application/json');
                echo json_encode([
                    'bookings' =>$bookings,
                    'holidays' => $holidays
                ]);
                exit;
            }
            $this->view('admin/admin-booking');
        }

        public function calendar(){
            $this->view('admin/admin-calendar');
        }

        public function payment($action = null){
            $paymentModel = new M_Payment();
            $payment = $paymentModel->paymentAdmin();

            if($action === 'api'){
                header('Content-Type: application/json');
                echo json_encode(['payment' => $payment]);
                exit;
            }
            $this->view('admin/admin-payment');
        }

    }