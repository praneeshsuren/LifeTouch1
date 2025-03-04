<?php

    class Receptionist extends Controller{


        public function __construct() {
            // Check if the user is logged in as a receptionist
            $this->checkAuth('receptionist');
        }
        
        public function index(){
            $this->view('receptionist/receptionist-dashboard');
        }

        public function announcements(){
            $this->view('receptionist/receptionist-announcements');
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
                            $temp['user_id'] = $temp['trainer_id'];
            
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);

                            $temp['status'] = 'Active';

                            //Handle image input
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = APPROOT. "/assets/images/Trainer/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;
                            
                                // Validate and move the file to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $data['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['image'] = "Failed to upload the file. Please try again.";
                                }
                            } elseif (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                                // Handle other file upload errors
                                $errors['image'] = "An error occurred during file upload. Please try again.";
                            } else {
                                // No file uploaded, set a default value or leave it empty
                                $data['image'] = null; // Or set a default placeholder if necessary
                            }

                            // Insert into User and Member models
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
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the Trainer model
                        $trainerModel = new M_Trainer;
                
                        // Validate the incoming data
                        if ($trainerModel->validate($_POST)) {
                            // Prepare the data to update the trainer
                            $data = [
                                'first_name'    => $_POST['first_name'],
                                'last_name'     => $_POST['last_name'],
                                'NIC_no'        => $_POST['NIC_no'],
                                'date_of_birth' => $_POST['date_of_birth'],
                                'home_address'  => $_POST['home_address'],
                                'contact_number'=> $_POST['contact_number'],
                                'gender'        => $_POST['gender'],
                                'email_address' => $_POST['email_address']
                            ];
                
                            $trainer_id = $_POST['trainer_id'];
                
                            // Handle image upload if a new file is provided
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = APPROOT . "/assets/images/Trainer/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;
                
                                // Attempt to move the uploaded file to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    // Save the new image filename to the data array
                                    $data['image'] = $fileName;
                
                                    // Optionally, delete the old image file if one exists
                                    $existingTrainer = $trainerModel->findByTrainerId($trainer_id);
                                    if (!empty($existingTrainer['image'])) {
                                        $oldImage = $targetDir . $existingTrainer['image'];
                                        if (file_exists($oldImage)) {
                                            unlink($oldImage);
                                        }
                                    }
                                } else {
                                    $_SESSION['error'] = "Failed to upload the new image. Please try again.";
                                    redirect('receptionist/trainers/viewTrainer?id=' . $trainer_id);
                                }
                            }
                
                            // Call the update function
                            if ($trainerModel->update($trainer_id, $data, 'trainer_id')) {
                                // Set a success session message
                                $_SESSION['success'] = "Trainer has been successfully updated!";
                                // Redirect to the trainer view page
                                redirect('receptionist/trainers/viewTrainer?id=' . $trainer_id);
                            } else {
                                // Handle update failure (optional)
                                $_SESSION['error'] = "There was an issue updating the trainer. Please try again.";
                                redirect('receptionist/trainers/viewTrainer?id=' . $trainer_id);
                            }
                        } else {
                            // If validation fails, pass errors to the view
                            $data = [
                                'errors' => $trainerModel->errors,
                                'trainer' => $_POST // Preserve form data for user correction
                            ];
                            // Render the view with errors and form data
                            $this->view('receptionist/receptionist-viewTrainer', $data);
                        }
                    } else {
                        // Redirect if the request is not a POST request
                        redirect('receptionist/trainers');
                    }
                    break;
                    

                case 'deleteTrainer':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                
                        // Begin the deletion process
                        if (!$userModel->delete($userId, 'user_id')) {
                
                            $_SESSION['success'] = "Trainer has been deleted successfully";
            
                            redirect('receptionist/trainers');
                        } 
                        else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the trainer. Please try again.";
                            redirect('receptionist/trainers/viewTrainer?id=' . $userId);
                        }

                    break;                    
                                       
        
                default:
                    // Fetch all trainers and pass to the view
                    $trainerModel = new M_Trainer;
                    $trainers = $trainerModel->findAll('created_at');
        
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

                            $temp['status'] = 'Active';
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

                case 'viewMember':
                    // Load the view to view a trainer
                    $memberModel = new M_Member;
                    $member = $memberModel->findByMemberId($_GET['id']);
        
                    $data = [
                        'member' => $member
                    ];
        
                    $this->view('receptionist/receptionist-viewMember', $data);
                    break;

                case 'updateMember':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the Trainer model
                        $memberModel = new M_Member;
                
                        // Validate the incoming data
                        if ($memberModel->validate($_POST)) {
                            // Prepare the data to update the trainer

                            $data = [
                                'first_name'    => $_POST['first_name'],
                                'last_name'     => $_POST['last_name'],
                                'NIC_no'        => $_POST['NIC_no'],
                                'date_of_birth' => $_POST['date_of_birth'],
                                'home_address'  => $_POST['home_address'],
                                'height'        => $_POST['height'],
                                'weight'        => $_POST['weight'],
                                'contact_number'=> $_POST['contact_number'],
                                'gender'        => $_POST['gender'],
                                'email_address' => $_POST['email_address']
                            ];

                            $member_id = $_POST['member_id'];
                
                            // Call the update function
                            if (!$memberModel->update($member_id, $data, 'member_id')) {
                                // Set a success session message
                                $_SESSION['success'] = "Member has been successfully updated!";
                                // Redirect to the trainer view page
                                redirect('receptionist/members/viewMember?id=' . $member_id);
                            } else {
                                // Handle update failure (optional)
                                $_SESSION['error'] = "There was an issue updating the member. Please try again.";
                                redirect('receptionist/members/viewMember?id=' . $member_id);
                            }
                        } else {
                            // If validation fails, pass errors to the view
                            $data = [
                                'errors' => $memberModel->errors,
                                'member' => $_POST // Preserve form data for user correction
                            ];
                            // Render the view with errors and form data
                            $this->view('receptionist/receptionist-viewMember', $data);
                        }
                    } else {
                        // Redirect if the request is not a POST request
                        redirect('receptionist/members');
                    }
                    break;
                
                case 'deleteMember':

                    $userModel = new M_User;
                
                    // Get the user ID from the GET parameters
                    $userId = $_GET['id'];
            
                    // Begin the deletion process
                    if (!$userModel->delete($userId, 'user_id')) {
            
                        $_SESSION['success'] = "Member has been deleted successfully";
        
                        redirect('receptionist/members');
                    } 
                    else {
                        // Handle deletion failure
                        $_SESSION['error'] = "There was an issue deleting the member. Please try again.";
                        redirect('receptionist/members/viewMember?id=' . $userId);
                    }

                    break;
                
                default:
                    // Fetch all members and pass to the view
                    $memberModel = new M_Member;
                    $members = $memberModel->findAll('created_at');

                    $data = [
                        'members' => $members
                    ];

                    $this->view('receptionist/receptionist-members', $data);
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
                    $this->view('receptionist/receptionist-booking');
                }
        
                public function calendar(){
                    $this->view('receptionist/receptionist-calendar');
                }
                public function holiday($action = null){
                    $holidayModal = new M_Holiday();
                    $holidays = $holidayModal->findAll();
                    $bookingModel = new M_Booking();
                    $bookings = $bookingModel->findAll();
                
                    if($action === 'api'){
                        header('Content-Type: application/json');
                        echo json_encode([
                            'holidays' => $holidays,
                            'bookings' => $bookings
                        ]);
                        exit;
                    } elseif ($action === 'add') {
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            header('Content-Type: application/json');
                    
                            $date = $_POST['holidayDate'] ?? null;
                            $reason = isset($_POST['holidayReason']) && $_POST['holidayReason'] !== '' ? $_POST['holidayReason'] : null;

                            $existingHoliday = $holidayModal->first(['date' => $date]);
                            if($existingHoliday){
                                echo json_encode(["success" => false, "message" => "A holiday already exists for this date"]);
                                exit;
                            }
                 
                            $data = [
                                'date' => $date,
                                'reason' => $reason
                            ];
                            
                            $result = $holidayModal->insert($data);
                            
                            echo json_encode([
                                "success" => $result ? true : false, 
                                "message" => $result ? "Holiday added successfully!" : "Failed to add holiday"
                            ]);
                            exit;
                        }
                    
                        // Ensure a response is always sent
                        echo json_encode(["success" => false, "message" => "Invalid request"]);
                        exit;
                    } elseif($action === "delete"){
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $id = $_POST['id'];
                    
                            if ($holidayModal->delete($id)) {
                                echo json_encode(["success" => true, "message" => "Holiday deleted successfully!"]);
                                exit;
                            } else {
                                echo json_encode(["success" => false, "message" => "Error deleting holiday."]);
                                exit;
                            }
                        }
                        echo json_encode(["success" => false, "message" => "Invalid request."]);
                        exit;
                    } elseif ($action === 'edit'){
                        header('Content-type: application/json');

                        $id = $_POST['id'] ?? null;
                        $reason = isset($_POST['reason']) && $_POST['reason'] !== '' ? $_POST['reason'] : null;

                        if (!$id) {
                            echo json_encode(["success" => false, "message" => "Missing required fields"]);
                            exit;
                        }

                        $data = ['reason' => $reason];
                        $result = $holidayModal->update($id, $data);

                        echo json_encode(
                            [
                                "success" => $result ? true : false,
                                "message" => $result ? "Holiday reason updated successfully!" : "Failed to update reason"
                            ]
                            );
                        exit;

                    }
                    $this->view('receptionist/receptionist-holiday'); 
                }
    }