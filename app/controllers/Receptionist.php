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
                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
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
                    }
                    else{
                        redirect('receptionist/trainers');
                    }
                    break;

                case 'viewTrainer':
                    // Load the view to view a trainer
                    $trainerModel = new M_Trainer;
                    $trainer = $trainerModel->findByTrainerId($_GET['id']);
        
                    $data = [
                        'trainer' => $trainer
                    ];
        
                    $this->view('receptionist/receptionist-viewTrainer', $data);
                    break;

                case 'updateTrainer':
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                
                        // Initialize the trainer model
                        $trainerModel = new M_Trainer;
                
                        // Extract trainer ID and other data
                        $trainer_id = $_POST['trainer_id']; // Assuming this is a hidden field in the form
                        $data = [
                            'first_name' => $_POST['first_name'],
                            'last_name' => $_POST['last_name'],
                            'gender' => $_POST['gender'],
                            'date_of_birth' => $_POST['date_of_birth'],
                            'home_address' => $_POST['home_address'],
                            'email_address' => $_POST['email_address'],
                            'contact_number' => $_POST['contact_number']
                        ];
                
                        // Attempt to update the trainer
                        if ($trainerModel->update($trainer_id, $data, 'trainer_id')) {
                            // Set a success message in the session
                            $_SESSION['success'] = "Trainer details updated successfully!";
                            
                            // Redirect to the trainer view page with the updated trainer ID as a query parameter
                            redirect("receptionist/trainers/viewTrainer?id={$trainer_id}");
                        } else {
                            // Set an error message in the session
                            $_SESSION['error'] = "Failed to update trainer details.";
                            
                            // Redirect back to the trainers list
                            redirect('receptionist/trainers/viewTrainer?id={$trainer_id}');
                        }
                        
                    } else {
                        // Redirect to trainers list if accessed without POST request
                        redirect('/receptionist/trainers');
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
                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
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
                    }
                    else{
                        redirect('receptionist/members');
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