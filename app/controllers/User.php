<?php

    class User extends Controller{

        public function member($action = null){

            switch ($action){

                case 'registerMember':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $member = new M_Member;
                        $user = new M_User;
            
                        if (!isset($_POST['membership_plan'])) {
                            $_POST['membership_plan'] = 'Monthly'; // Default value
                        }

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

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Member/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $temp['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($temp['image'])) {
                                $temp['image'] = null;
                            }


                            $user->insert($temp);
                            $member->insert($temp);
            
                            // Set a session message or flag for success
                            $_SESSION['success'] = "Member has been successfully registered!";
            
                            // Redirect to trainers list with success message
                            redirect('admin/members');
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $member->errors);
                            $this->view('admin/admin-createMember', $data);
                        }
                    }
                    else{
                        redirect('admin/members');
                    }

                    break;

                case 'updateMember':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the Trainer model
                        $memberModel = new M_Member;
                        $userRole = $_SESSION['role'];
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
                                'email_address' => $_POST['email_address'],
                                'membership_plan' => $_POST['membership_plan'],
                                'image'         => $_POST['image']
                            ];

                            $member_id = $_POST['member_id'];
                            $member = $memberModel->findByMemberId($member_id); // Fetch the existing member data

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Member/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $data['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($data['image'])) {
                                $data['image'] = $member->image; // Preserve the existing image if no new one is uploaded
                            }
                
                            // Call the update function
                            if (!$memberModel->update($member_id, $data, 'member_id')) {
                                // Set a success session message
                                $_SESSION['success'] = "Member has been successfully updated!";

                                // Check the user role and redirect accordingly
                                if ($userRole == 'Admin') {
                                    redirect('admin/members/viewMember?id=' . $member_id);
                                } elseif ($userRole == 'Receptionist') {
                                    redirect('receptionist/members/viewMember?id=' . $member_id);
                                } elseif ($userRole == 'Trainer') {
                                    redirect('trainer/members/viewMember?id=' . $member_id);
                                } elseif ($userRole == 'Manager') {
                                    redirect('manager/members/viewMember?id=' . $member_id);
                                }
                            } else {
                                // Handle update failure (optional)
                                $_SESSION['error'] = "There was an issue updating the member. Please try again.";
                                redirect('admin/members/viewMember?id=' . $member_id);
                            }
                        } else {
                            // If validation fails, pass errors to the view
                            $data = [
                                'errors' => $memberModel->errors,
                                'member' => $_POST // Preserve form data for user correction
                            ];
                            // Render the view with errors and form data
                            $this->view('admin/admin-viewMember', $data);
                        }
                    } else {
                        // Redirect if the request is not a POST request
                        redirect('admin/members');
                    }

                    break;

                case 'deleteMember':

                    $userModel = new M_User;
                
                    // Get the user ID from the GET parameters
                    $userId = $_GET['id'];
            
                    // Begin the deletion process
                    if (!$userModel->delete($userId, 'user_id')) {
            
                        $_SESSION['success'] = "Member has been deleted successfully";
        
                        redirect('admin/members');
                    } 
                    else {
                        // Handle deletion failure
                        $_SESSION['error'] = "There was an issue deleting the member. Please try again.";
                        redirect('admin/members/viewMember?id=' . $userId);
                    }

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

        
        
        public function trainer($action = null) {

            switch ($action) {
        
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

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Trainer/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $temp['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($temp['image'])) {
                                $temp['image'] = null;
                            }

                            // Insert into User and Member models
                            $user->insert($temp);
                            $trainer->insert($temp);
            
                            // Set a session message or flag for success
                            $_SESSION['success'] = "Trainer has been successfully registered!";
            
                            // Redirect to trainers list with success message
                            redirect('admin/trainers');
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $trainer->errors);
                            $this->view('admin/admin-createTrainer', $data);
                        }
                    }
                    else{
                        redirect('admin/trainers');
                    }
                    break;

                case 'updateTrainer':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the Trainer model
                        $trainerModel = new M_Trainer;
                        $userRole = $_SESSION['role'];
                
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
                                'email_address' => $_POST['email_address'],
                                'image'         => $_POST['image']
                            ];

                            $trainer_id = $_POST['trainer_id'];
                            $trainer = $trainerModel->findByTrainerId($trainer_id);

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Trainer/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $data['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($data['image'])) {
                                $data['image'] = $trainer->image; // Preserve the existing image if no new one is uploaded
                            }
                
                            // Call the update function
                            if (!$trainerModel->update($trainer_id, $data, 'trainer_id')) {
                                // Set a success session message
                                $_SESSION['success'] = "Trainer has been successfully updated!";

                                // Check the user role and redirect accordingly
                                if ($userRole == 'Admin') {
                                    redirect('admin/trainers/viewTrainer?id=' . $trainer_id);
                                } elseif ($userRole == 'Receptionist') {
                                    redirect('receptionist/trainers/viewTrainer?id=' . $trainer_id);
                                } elseif ($userRole == 'Trainer') {
                                    redirect('trainer/trainers/viewTrainer?id=' . $trainer_id);
                                } elseif ($userRole == 'Manager') {
                                    redirect('manager/trainers/viewTrainer?id=' . $trainer_id);
                                }
                                
                            } else {
                                // Handle update failure (optional)
                                $_SESSION['error'] = "There was an issue updating the trainer. Please try again.";
                                redirect('admin/trainers/viewTrainer?id=' . $trainer_id);
                            }
                        } else {
                            // If validation fails, pass errors to the view
                            $data = [
                                'errors' => $trainerModel->errors,
                                'trainer' => $_POST // Preserve form data for user correction
                            ];
                            // Render the view with errors and form data
                            $this->view('admin/admin-viewTrainer', $data);
                        }
                    } else {
                        // Redirect if the request is not a POST request
                        redirect('admin/trainers');
                    }
                    break;

                case 'deleteTrainer':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                
                        // Begin the deletion process
                        if (!$userModel->delete($userId, 'user_id')) {
                
                            $_SESSION['success'] = "Trainer has been deleted successfully";
            
                            redirect('admin/trainers');
                        } 
                        else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the trainer. Please try again.";
                            redirect('admin/trainers/viewTrainer?id=' . $userId);
                        }

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

        public function receptionist($action = null) {
            switch ($action) {
        
                case 'registerReceptionist':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $receptionist = new M_Receptionist;
                        $user = new M_User;
            
                        if ($receptionist->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
            
                            // Set receptionist_id based on gender
                            if ($temp['gender'] == 'Male') {
                                $temp['receptionist_id'] = 'RT/M/';
                            } elseif ($temp['gender'] == 'Female') {
                                $temp['receptionist_id'] = 'RT/F/';
                            } else {
                                $temp['receptionist_id'] = 'RT/O/';
                            }
            
                            // Generate a 4-digit receptionist ID offset
                            $offset = str_pad($receptionist->countAll() + 1, 4, '0', STR_PAD_LEFT);
                            $temp['receptionist_id'] .= $offset;
                            $temp['user_id'] = $temp['receptionist_id'];
            
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);

                            $temp['status'] = 'Active';

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Receptionist/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $temp['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($temp['image'])) {
                                $temp['image'] = null;
                            }

                            // Insert into User and Member models
                            $user->insert($temp);
                            $receptionist->insert($temp);
            
                            // Set a session message or flag for success
                            $_SESSION['success'] = "Receptionist has been successfully registered!";
            
                            // Redirect to receptionists list with success message
                            redirect('admin/receptionists');
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $receptionist->errors);
                            $this->view('admin/admin-createReceptionist', $data);
                        }
                    }
                    else{
                        redirect('admin/receptionists');
                    }
                    break;

                case 'updateReceptionist':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the receptionist model
                        $receptionistModel = new M_Receptionist;
                        // Validate the incoming data
                        if ($receptionistModel->validate($_POST)) {
                            // Prepare the data to update the receptionist


                            $data = [
                                'first_name'    => $_POST['first_name'],
                                'last_name'     => $_POST['last_name'],
                                'NIC_no'        => $_POST['NIC_no'],
                                'date_of_birth' => $_POST['date_of_birth'],
                                'home_address'  => $_POST['home_address'],
                                'contact_number'=> $_POST['contact_number'],
                                'gender'        => $_POST['gender'],
                                'email_address' => $_POST['email_address'],
                                'image'         => $_POST['image']
                            ];

                            $receptionist_id = $_POST['receptionist_id'];
                            $receptionist = $receptionistModel->findByReceptionistId($receptionist_id); // Fetch the existing receptionist data

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Receptionist/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $data['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($data['image'])) {
                                $data['image'] = $receptionist->image; // Preserve the existing image if no new one is uploaded
                            }
                
                
                            // Call the update function
                            if (!$receptionistModel->update($receptionist_id, $data, 'receptionist_id')) {
                                // Set a success session message
                                $_SESSION['success'] = "Receptionist has been successfully updated!";
                                // Redirect to the receptionist view page
                                redirect('admin/receptionists/viewReceptionist?id=' . $receptionist_id);
                            } else {
                                // Handle update failure (optional)
                                $_SESSION['error'] = "There was an issue updating the receptionist. Please try again.";
                                redirect('admin/receptionists/viewReceptionist?id=' . $receptionist_id);
                            }
                        } else {

                            // If validation fails, pass errors to the view
                            $data = [
                                'errors' => $receptionistModel->errors,
                                'receptionist' => $_POST // Preserve form data for user correction
                            ];
                            // Render the view with errors and form data
                            $this->view('admin/admin-viewReceptionist', $data);
                        }
                    } else {
                        // Redirect if the request is not a POST request
                        redirect('admin/receptionists');
                    }
                    break;

                case 'deleteReceptionist':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                
                        // Begin the deletion process
                        if (!$userModel->delete($userId, 'user_id')) {
                
                            $_SESSION['success'] = "Receptionist has been deleted successfully";
            
                            redirect('admin/receptionists');
                        } 
                        else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the receptionist. Please try again.";
                            redirect('admin/receptionists/viewReceptionist?id=' . $userId);
                        }

                    break;                    
                                       
        
                default:
                    // Fetch all receptionists and pass to the view
                    $receptionistModel = new M_Receptionist;
                    $receptionists = $receptionistModel->findAll('created_at');
        
                    $data = [
                        'receptionists' => $receptionists
                    ];
        
                    $this->view('admin/admin-receptionists', $data);
                    break;
            }
        }

        public function manager($action = null) {
            switch ($action) {

                case 'registerManager':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $manager = new M_Manager;
                        $user = new M_User;
            
                        if ($manager->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
            
                            // Set manager_id based on gender
                            if ($temp['gender'] == 'Male') {
                                $temp['manager_id'] = 'MR/M/';
                            } elseif ($temp['gender'] == 'Female') {
                                $temp['manager_id'] = 'MR/F/';
                            } else {
                                $temp['manager_id'] = 'MR/O/';
                            }
            
                            // Generate a 4-digit manager ID offset
                            $offset = str_pad($manager->countAll() + 1, 4, '0', STR_PAD_LEFT);
                            $temp['manager_id'] .= $offset;
                            $temp['user_id'] = $temp['manager_id'];
            
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);

                            $temp['status'] = 'Active';

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Manager/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $temp['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($temp['image'])) {
                                $temp['image'] = null;
                            }

                            // Insert into User and Member models
                            $user->insert($temp);
                            $manager->insert($temp);
            
                            // Set a session message or flag for success
                            $_SESSION['success'] = "Manager has been successfully registered!";
            
                            // Redirect to managers list with success message
                            redirect('admin/managers');
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $manager->errors);
                            $this->view('admin/admin-createManager', $data);
                        }
                    }
                    else{
                        redirect('admin/managers');
                    }
                    break;

                case 'updateManager':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the manager model
                        $managerModel = new M_Manager;
                
                        // Fetch the existing manager data
                        $manager_id = $_POST['manager_id'];
                        $manager = $managerModel->findByManagerId($manager_id);
                
                        // Start with the existing data (preserve current values)
                        $data = [
                            'first_name'    => $manager->first_name,
                            'last_name'     => $manager->last_name,
                            'NIC_no'        => $manager->NIC_no,
                            'date_of_birth' => $manager->date_of_birth,
                            'home_address'  => $manager->home_address,
                            'contact_number'=> $manager->contact_number,
                            'gender'        => $manager->gender,
                            'email_address' => $manager->email_address,
                            'image'         => $manager->image // Preserve current image
                        ];
                
                        // Check and update only the fields that have been modified
                        if (isset($_POST['first_name']) && $_POST['first_name'] != $manager->first_name) {
                            $data['first_name'] = $_POST['first_name'];
                        }
                        if (isset($_POST['last_name']) && $_POST['last_name'] != $manager->last_name) {
                            $data['last_name'] = $_POST['last_name'];
                        }
                        if (isset($_POST['NIC_no']) && $_POST['NIC_no'] != $manager->NIC_no) {
                            $data['NIC_no'] = $_POST['NIC_no'];
                        }
                        if (isset($_POST['date_of_birth']) && $_POST['date_of_birth'] != $manager->date_of_birth) {
                            $data['date_of_birth'] = $_POST['date_of_birth'];
                        }
                        if (isset($_POST['home_address']) && $_POST['home_address'] != $manager->home_address) {
                            $data['home_address'] = $_POST['home_address'];
                        }
                        if (isset($_POST['contact_number']) && $_POST['contact_number'] != $manager->contact_number) {
                            $data['contact_number'] = $_POST['contact_number'];
                        }
                        if (isset($_POST['gender']) && $_POST['gender'] != $manager->gender) {
                            $data['gender'] = $_POST['gender'];
                        }
                        if (isset($_POST['email_address']) && $_POST['email_address'] != $manager->email_address) {
                            $data['email_address'] = $_POST['email_address'];
                        }
                
                        // Handle file upload if exists and if changed
                        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                            $targetDir = "assets/images/Manager/";
                            $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                            $targetFile = $targetDir . $fileName;
                
                            // Validate the file (e.g., check file type and size) and move it to the target directory
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                $data['image'] = $fileName; // Save the new filename for the database
                            } else {
                                $errors['file'] = "Failed to upload the file. Please try again.";
                            }
                        }
                
                        // Call the update function
                        if (!$managerModel->update($manager_id, $data, 'manager_id')) {
                            // Set a success session message
                            $_SESSION['success'] = "Manager has been successfully updated!";
                            // Redirect to the manager view page
                            redirect('admin/managers/viewManager?id=' . $manager_id);
                        } else {
                            // Handle update failure (optional)
                            $_SESSION['error'] = "There was an issue updating the manager. Please try again.";
                            redirect('admin/managers/viewManager?id=' . $manager_id);
                        }
                
                    } else {
                        // If the request is not a POST request
                        redirect('admin/managers');
                    }
                    break;
                    

                case 'deleteManager':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                
                        // Begin the deletion process
                        if (!$userModel->delete($userId, 'user_id')) {
                
                            $_SESSION['success'] = "Manager has been deleted successfully";
            
                            redirect('admin/managers');
                        } 
                        else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the manager. Please try again.";
                            redirect('admin/managers/viewManager?id=' . $userId);
                        }

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

        public function admin($action = null) {
            switch ($action) {
        
                case 'registerAdmin':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $admin = new M_Admin;
                        $user = new M_User;
            
                        if ($admin->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
            
                            // Set admin_id based on gender
                            if ($temp['gender'] == 'Male') {
                                $temp['admin_id'] = 'AD/M/';
                            } elseif ($temp['gender'] == 'Female') {
                                $temp['admin_id'] = 'AD/F/';
                            } else {
                                $temp['admin_id'] = 'AD/O/';
                            }
            
                            // Generate a 4-digit admin ID offset
                            $offset = str_pad($admin->countAll() + 1, 4, '0', STR_PAD_LEFT);
                            $temp['admin_id'] .= $offset;
                            $temp['user_id'] = $temp['admin_id'];
            
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);

                            $temp['status'] = 'Active';

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Admin/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                                $targetFile = $targetDir . $fileName;

                                // Validate the file (e.g., check file type and size) and move it to the target directory
                                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                    $temp['image'] = $fileName; // Save the filename for the database
                                } else {
                                    $errors['file'] = "Failed to upload the file. Please try again.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($temp['image'])) {
                                $temp['image'] = null;
                            }

                            // Insert into User and Member models
                            $user->insert($temp);
                            $admin->insert($temp);
            
                            // Set a session message or flag for success
                            $_SESSION['success'] = "Admin has been successfully registered!";
            
                            // Redirect to admins list with success message
                            redirect('admin/admins');
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $admin->errors);
                            $this->view('admin/admin-createAdmin', $data);
                        }
                    }
                    else{
                        redirect('admin/admins');
                    }
                    break;

                case 'updateAdmin':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the admin model
                        $adminModel = new M_Admin;
                
                        // Fetch the existing admin data
                        $admin_id = $_POST['admin_id'];
                        $admin = $adminModel->findByAdminId($admin_id);
                
                        // Start with the existing data (preserve current values)
                        $data = [
                            'first_name'    => $admin->first_name,
                            'last_name'     => $admin->last_name,
                            'NIC_no'        => $admin->NIC_no,
                            'date_of_birth' => $admin->date_of_birth,
                            'home_address'  => $admin->home_address,
                            'contact_number'=> $admin->contact_number,
                            'gender'        => $admin->gender,
                            'email_address' => $admin->email_address,
                            'image'         => $admin->image // Preserve current image
                        ];
                
                        // Check and update only the fields that have been modified
                        if (isset($_POST['first_name']) && $_POST['first_name'] != $admin->first_name) {
                            $data['first_name'] = $_POST['first_name'];
                        }
                        if (isset($_POST['last_name']) && $_POST['last_name'] != $admin->last_name) {
                            $data['last_name'] = $_POST['last_name'];
                        }
                        if (isset($_POST['NIC_no']) && $_POST['NIC_no'] != $admin->NIC_no) {
                            $data['NIC_no'] = $_POST['NIC_no'];
                        }
                        if (isset($_POST['date_of_birth']) && $_POST['date_of_birth'] != $admin->date_of_birth) {
                            $data['date_of_birth'] = $_POST['date_of_birth'];
                        }
                        if (isset($_POST['home_address']) && $_POST['home_address'] != $admin->home_address) {
                            $data['home_address'] = $_POST['home_address'];
                        }
                        if (isset($_POST['contact_number']) && $_POST['contact_number'] != $admin->contact_number) {
                            $data['contact_number'] = $_POST['contact_number'];
                        }
                        if (isset($_POST['gender']) && $_POST['gender'] != $admin->gender) {
                            $data['gender'] = $_POST['gender'];
                        }
                        if (isset($_POST['email_address']) && $_POST['email_address'] != $admin->email_address) {
                            $data['email_address'] = $_POST['email_address'];
                        }
                
                        // Handle file upload if exists and if changed
                        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                            $targetDir = "assets/images/Admin/";
                            $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                            $targetFile = $targetDir . $fileName;
                
                            // Validate the file (e.g., check file type and size) and move it to the target directory
                            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                $data['image'] = $fileName; // Save the new filename for the database
                            } else {
                                $errors['file'] = "Failed to upload the file. Please try again.";
                            }
                        }
                
                        // If no image uploaded, preserve the existing image
                        if (!isset($data['image'])) {
                            $data['image'] = $admin->image; // Preserve the existing image if no new one is uploaded
                        }
                
                        // Call the update function
                        if (!$adminModel->update($admin_id, $data, 'admin_id')) {
                            // Set a success session message
                            $_SESSION['success'] = "Admin has been successfully updated!";
                            // Redirect to the admin view page
                            redirect('admin/admins/viewAdmin?id=' . $admin_id);
                        } else {
                            // Handle update failure (optional)
                            $_SESSION['error'] = "There was an issue updating the admin. Please try again.";
                            redirect('admin/admins/viewAdmin?id=' . $admin_id);
                        }
                
                    } else {
                        // If the request is not a POST request
                        redirect('admin/admins');
                    }
                    break;
                    

                case 'deleteAdmin':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                
                        // Begin the deletion process
                        if (!$userModel->delete($userId, 'user_id')) {
                
                            $_SESSION['success'] = "Admin has been deleted successfully";
            
                            redirect('admin/admins');
                        } 
                        else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the admin. Please try again.";
                            redirect('admin/admins/viewAdmin?id=' . $userId);
                        }

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


    }