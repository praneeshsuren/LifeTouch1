<?php

    class Receptionist extends Controller{
        
        public function index(){
            $this->view('receptionist/receptionist-dashboard');
        }

        public function trainers($action = null) {
            switch ($action) {
                case 'createTrainer':
                    // Load the form view to create a trainer
                    $this->view('receptionist/receptionist-createTrainer');
                    break;
        
                case 'registerTrainer':
                    $trainer = new M_Trainer;
                    $user = new M_User;
        
                    if ($trainer->validate($_POST) && $user->validate($_POST)) {
                        $temp = $_POST;
        
                        // Set trainer_id based on gender
                        if ($temp['gender'] == 'Male') {
                            $temp['trainer_id'] = 'TN/M/';
                        } elseif ($temp['gender'] == 'Female') {
                            $temp['trainer_id'] = 'TN/F/';
                        } else {
                            $temp['trainer_id'] = 'TN/O/';
                        }
        
                        // Generate a 4-digit trainer ID offset
                        $offset = str_pad($trainer->countAll() + 1, 4, '0', STR_PAD_LEFT);
                        $temp['trainer_id'] .= $offset;
                        $temp['user_id'] = $temp['trainer_id']; // Assuming trainer_id maps to user_id in this case
        
                        $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                        // Insert into User and Trainer models
                        $user->insert($temp);
                        $trainer->insert($temp);
        
                        // Set a session message or flag for success
                        $_SESSION['success'] = "Trainer has been successfully registered!";
        
                        // Redirect to trainers list with success message
                        redirect('receptionist/trainers');
                    } else {
                        // Merge validation errors and pass to the view
                        $data['errors'] = array_merge($user->errors, $trainer->errors);
                        $this->view('receptionist/receptionist-createTrainer', $data);
                    }
                    break;
        
                default:
                    // Fetch all trainers and pass to the view
                    $trainerModel = new M_Trainer;
                    $trainers = $trainerModel->findAll();
        
                    $data = [
                        'trainers' => $trainers
                    ];
        
                    $this->view('receptionist/receptionist-trainers', $data);
                    break;
            }
        }

        public function members($action = null) {
            switch ($action) {
                case 'createMember':
                    // Load the form view to create a member
                    $this->view('receptionist/receptionist-createMember');
                    break;
        
                case 'registerMember':
                    $member = new M_Member;
                    $user = new M_User;
        
                    if ($member->validate($_POST) && $user->validate($_POST)) {
                        $temp = $_POST;
        
                        // Set trainer_id based on gender
                        if ($temp['gender'] == 'Male') {
                            $temp['member_id'] = 'MB/M/';
                        } elseif ($temp['gender'] == 'Female') {
                            $temp['member_id'] = 'MB/F/';
                        } else {
                            $temp['member_id'] = 'MB/O/';
                        }
        
                        // Generate a 4-digit trainer ID offset
                        $offset = str_pad($member->countAll() + 1, 4, '0', STR_PAD_LEFT);
                        $temp['member_id'] .= $offset;
                        $temp['user_id'] = $temp['member_id'];
        
                        $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                        // Insert into User and Member models
                        $user->insert($temp);
                        $member->insert($temp);
        
                        // Set a session message or flag for success
                        $_SESSION['success'] = "Member has been successfully registered!";
        
                        // Redirect to trainers list with success message
                        redirect('receptionist/members');
                    } else {
                        // Merge validation errors and pass to the view
                        $data['errors'] = array_merge($user->errors, $member->errors);
                        $this->view('receptionist/receptionist-createMember', $data);
                    }
        
                    break;
                
                default:
                    // Fetch all members and pass to the view
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll();

                    $data = [
                        'members' => $members
                    ];

                    $this->view('receptionist/receptionist-members', $data);
                    break;
            }
        }
        
        
        

    }