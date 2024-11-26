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
                
                default:
                    // Fetch all members and pass to the view
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll();

                    $data = [
                        'members' => $members
                    ];

                    $this->view('trainer/trainer-members', $data);
                    break;

            }
        }

    }