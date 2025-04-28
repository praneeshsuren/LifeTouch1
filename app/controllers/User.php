<?php

    class User extends Controller{

        public function member($action = null){

            switch ($action){

                case 'registerMember':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $member = new M_Member;
                        $user = new M_User;
                        $userRole = $_SESSION['role'];
                
                        // Set default membership plan if not provided
                        $_POST['membership_plan'] = $_POST['membership_plan'] ?? 'Monthly';
                
                        // Validate user and member data
                        if ($member->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
                
                            // Set member_id based on gender
                            $temp['member_id'] = match($temp['gender']) {
                                'Male' => 'MB/M/',
                                'Female' => 'MB/F/',
                                default => 'MB/O/',
                            };
                
                            // Get the last member_id from the database
                            $lastId = $member->getLastMemberId();
                            $lastMemberId = $lastId ? $lastId->id : 0; // Default to 0 if no members exist
                
                            // Get the last 4 digits of the member_id and increment by 1
                            $lastMemberOffset = substr($lastMemberId, -4);  // Extract last 4 digits
                            $newOffset = str_pad((int)$lastMemberOffset + 1, 4, '0', STR_PAD_LEFT); // Increment and pad to 4 digits
                            $temp['member_id'] .= $newOffset; // Append to member_id
                
                            // Set user_id and hash password
                            $temp['user_id'] = $temp['member_id'];
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                            $temp['status'] = 'Active';
                            $temp['id'] = $lastMemberId + 1;
                
                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Member/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']);
                                $targetFile = $targetDir . $fileName;
                
                                // Validate file type and size
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Example file types
                                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                    if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max file size: 5MB
                                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                            $temp['image'] = $fileName; // Save filename for database
                                        } else {
                                            $errors['file'] = "Failed to upload the image. Please try again.";
                                        }
                                    } else {
                                        $errors['file'] = "The file is too large. Max size is 5MB.";
                                    }
                                } else {
                                    $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
                                }
                            }
                
                            // Insert the new member and user into the database
                            if (empty($errors)) {
                                $user->insert($temp);
                                $member->insert($temp);
                
                                // Set success message in session
                                $_SESSION['success'] = "Member has been successfully registered!";
                
                                // Redirect based on the user role
                                $roleRedirectMap = [
                                    'Admin' => 'admin/members',
                                    'Receptionist' => 'receptionist/members',
                                    'Manager' => 'manager/members'
                                ];
                
                                if (isset($roleRedirectMap[$userRole])) {
                                    redirect($roleRedirectMap[$userRole]);
                                } else {
                                    // Handle case where user role is unrecognized
                                    redirect('error/roleNotFound');
                                }
                            } else {
                                // Merge validation errors and pass them to the view
                                $data['errors'] = array_merge($user->errors, $member->errors, $errors);
                
                                // Display the relevant view based on the user role
                                $roleViewMap = [
                                    'Admin' => 'admin/admin-createMember',
                                    'Receptionist' => 'receptionist/receptionist-createMember',
                                    'Manager' => 'manager/manager-createMember'
                                ];
                
                                $view = $roleViewMap[$userRole] ?? 'error/roleNotFound';
                                $this->view($view, $data);
                            }
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $member->errors);
                
                            // Display the relevant view based on the user role
                            $roleViewMap = [
                                'Admin' => 'admin/admin-createMember',
                                'Receptionist' => 'receptionist/receptionist-createMember',
                                'Manager' => 'manager/manager-createMember'
                            ];
                
                            $view = $roleViewMap[$userRole] ?? '404';
                            $this->view($view, $data);
                        }
                    }
                    else{
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/trainers');
                                break;
                            case 'Receptionist':
                                redirect('receptionist/trainers');
                                break;
                            case 'Manager':
                                redirect('manager/trainers');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                
                    break;
                

                    case 'updateMember':

                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $memberModel = new M_Member;
                            $userRole = $_SESSION['role'];
                            $member_id = $_POST['member_id'];
                    
                            // Validate the incoming data
                            if ($memberModel->validate($_POST)) {
                                // Fetch existing member data
                                $member = $memberModel->findByMemberId($member_id);
                    
                                // Prepare data to update (preserving existing values)
                                $data = [
                                    'first_name'    => $_POST['first_name'] ?? $member->first_name,
                                    'last_name'     => $_POST['last_name'] ?? $member->last_name,
                                    'NIC_no'        => $_POST['NIC_no'] ?? $member->NIC_no,
                                    'date_of_birth' => $_POST['date_of_birth'] ?? $member->date_of_birth,
                                    'home_address'  => $_POST['home_address'] ?? $member->home_address,
                                    'height'        => $_POST['height'] ?? $member->height,
                                    'weight'        => $_POST['weight'] ?? $member->weight,
                                    'contact_number'=> $_POST['contact_number'] ?? $member->contact_number,
                                    'gender'        => $_POST['gender'] ?? $member->gender,
                                    'email_address' => $_POST['email_address'] ?? $member->email_address,
                                    'membership_plan'=> $_POST['membership_plan'] ?? $member->membership_plan,
                                    'image'         => $member->image // Preserve current image by default
                                ];
                    
                                // Handle file upload if exists
                                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                    $targetDir = "assets/images/Member/";
                                    $fileName = time() . "_" . basename($_FILES['image']['name']);
                                    $targetFile = $targetDir . $fileName;
                    
                                    // Validate file type and size
                                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                                    if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                        if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max size 5MB
                                            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                                $data['image'] = $fileName;
                                            } else {
                                                $errors['file'] = "Failed to upload the file. Please try again.";
                                            }
                                        } else {
                                            $errors['file'] = "The file is too large. Max size is 5MB.";
                                        }
                                    } else {
                                        $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
                                    }
                                }
                    
                                // Call the update function if no errors
                                if (empty($errors) && $memberModel->update($member_id, $data, 'member_id')) {
                                    $_SESSION['success'] = "Member has been successfully updated!";
                                    redirect("{$userRole}/members/viewMember?id={$member_id}");
                                } else {
                                    $_SESSION['error'] = "There was an issue updating the member. Please try again.";
                                    redirect("{$userRole}/members/viewMember?id={$member_id}");
                                }
                            } else {
                                // Validation failed, pass errors and form data
                                $data = [
                                    'errors' => $memberModel->errors,
                                    'member' => $_POST
                                ];

                                // Load the appropriate view based on the user role
                                $viewPath = $userRole . '-viewMember?id='. $member_id; // Create the view path dynamically
                                $this->view("{$userRole}/{$viewPath}", $data); // Dynamic view loading
                            }

                        } else {
                            // Redirect based on the user role
                            $userRole = $_SESSION['role'];
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/members');
                                    break;
                                case 'Receptionist':
                                    redirect('receptionist/members');
                                    break;
                                case 'Manager':
                                    redirect('manager/members');
                                    break;
                                case 'Trainer':
                                    redirect('trainer/members');
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        }
                        
                        break;
                    

                case 'deleteMember':
                    $userModel = new M_User;
                
                    // Get the user ID from the GET parameters
                    $userId = $_GET['id'];
                    $userRole = $_SESSION['role'];
                
                    // Check if the user has the required permission to delete members
                    if (!isset($userId) || empty($userId)) {
                        $_SESSION['error'] = "Invalid member ID.";
                        redirect("{$userRole}/members");
                    }
                
                    // Begin the deletion process
                    if ($userModel->delete($userId, 'user_id')) {
                        // Set success message if the deletion is successful
                        $_SESSION['success'] = "Member has been deleted successfully.";
                
                        // Redirect based on the user's role
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/members');
                                break;
                            case 'Receptionist':
                                redirect('receptionist/members');
                                break;
                            case 'Manager':
                                redirect('manager/members');
                                break;
                            case 'Trainer':
                                redirect('trainer/members');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    } else {
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/members/viewMember?id=' . $userId);
                                break;
                            case 'Receptionist':
                                redirect('receptionist/members/viewMember?id=' . $userId);
                                break;
                            case 'Manager':
                                redirect('manager/members/viewMember?id=' . $userId);
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                
                    break;
                
            }
            

        }

        
        
        public function trainer($action = null) {

            switch ($action) {
        
                case 'registerTrainer':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $trainer = new M_Trainer;
                        $user = new M_User;
                        $userRole = $_SESSION['role'];
            
                        if ($trainer->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
            
                            $temp['trainer_id'] = match($temp['gender']) {
                                'Male' => 'TN/M/',
                                'Female' => 'TN/F/',
                                default => 'TN/O/',
                            };
            
                            // Get the last member_id from the database
                            $lastId = $trainer->getLastTrainerId();
                            $lastTrainerId = $lastId ? $lastId->id : 0; // Default to 0 if no members exist
                
                            // Get the last 4 digits of the member_id and increment by 1
                            $lastMemberOffset = substr($lastTrainerId, -4);  // Extract last 4 digits
                            $newOffset = str_pad((int)$lastMemberOffset + 1, 4, '0', STR_PAD_LEFT); // Increment and pad to 4 digits
                            $temp['trainer_id'] .= $newOffset; // Append to member_id
                
                            // Set user_id and hash password
                            $temp['user_id'] = $temp['trainer_id'];
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                            $temp['status'] = 'Active';
                            $temp['id'] = $lastTrainerId + 1;
                
                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Trainer/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']);
                                $targetFile = $targetDir . $fileName;
                
                                // Validate file type and size
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Example file types
                                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                    if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max file size: 5MB
                                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                            $temp['image'] = $fileName; // Save filename for database
                                        } else {
                                            $errors['file'] = "Failed to upload the image. Please try again.";
                                        }
                                    } else {
                                        $errors['file'] = "The file is too large. Max size is 5MB.";
                                    }
                                } else {
                                    $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
                                }
                            }
                
                            if (empty($errors)) {
                                $user->insert($temp);
                                $trainer->insert($temp);
                
                                // Set success message in session
                                $_SESSION['success'] = "Trainer has been successfully registered!";
                
                                // Redirect based on the user role
                                $roleRedirectMap = [
                                    'Admin' => 'admin/trainers',
                                    'Receptionist' => 'receptionist/trainers',
                                    'Manager' => 'manager/trainers'
                                ];
                
                                if (isset($roleRedirectMap[$userRole])) {
                                    redirect($roleRedirectMap[$userRole]);
                                } else {
                                    // Handle case where user role is unrecognized
                                    redirect('404');
                                }
                            } else {
                                // Merge validation errors and pass them to the view
                                $data['errors'] = array_merge($user->errors, $trainer->errors, $errors);
                
                                // Display the relevant view based on the user role
                                $roleViewMap = [
                                    'Admin' => 'admin/admin-createTrainer',
                                    'Receptionist' => 'receptionist/receptionist-createTrainer',
                                    'Manager' => 'manager/manager-createTrainer'
                                ];
                
                                $view = $roleViewMap[$userRole] ?? 'error/roleNotFound';
                                $this->view($view, $data);
                            }
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $trainer->errors);
                
                            // Display the relevant view based on the user role
                            $roleViewMap = [
                                'Admin' => 'admin/admin-createTrainer',
                                'Receptionist' => 'receptionist/receptionist-createTrainer',
                                'Manager' => 'manager/manager-createTrainer'
                            ];
                
                            $view = $roleViewMap[$userRole] ?? '404';
                            $this->view($view, $data);
                        }
                    }
                    else{
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/trainers');
                                break;
                            case 'Receptionist':
                                redirect('receptionist/trainers');
                                break;
                            case 'Manager':
                                redirect('manager/trainers');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;

                case 'updateTrainer':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the Trainer model
                        $trainerModel = new M_Trainer;
                        $trainer_id = $_POST['trainer_id'];
                        $userRole = $_SESSION['role'];
                
                            // Prepare the data to update the trainer
                            $trainer = $trainerModel->findByTrainerId($trainer_id);

                            $data = [
                                'first_name'    => $_POST['first_name'] ?? $trainer->first_name,
                                'last_name'     => $_POST['last_name'] ?? $trainer->last_name,
                                'NIC_no'        => $_POST['NIC_no'] ?? $trainer->NIC_no,
                                'date_of_birth' => $_POST['date_of_birth'] ?? $trainer->date_of_birth,
                                'home_address'  => $_POST['home_address'] ?? $trainer->home_address,
                                'contact_number'=> $_POST['contact_number'] ?? $trainer->contact_number,
                                'gender'        => $_POST['gender'] ?? $trainer->gender,
                                'email_address' => $_POST['email_address'] ?? $trainer->email_address,
                                'image'         => $trainer->image // Preserve current image by default
                            ];

                            // Validate the incoming data
                            if($trainerModel->validate($data)){

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Trainer/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']);
                                $targetFile = $targetDir . $fileName;
                
                                // Validate file type and size
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                    if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max size 5MB
                                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                            $data['image'] = $fileName;
                                        } else {
                                            $errors['file'] = "Failed to upload the file. Please try again.";
                                        }
                                    } else {
                                        $errors['file'] = "The file is too large. Max size is 5MB.";
                                    }
                                } else {
                                    $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
                                }
                            }
                
                            // Call the update function if no errors
                            if (empty($errors) && $trainerModel->update($trainer_id, $data, 'trainer_id')) {
                                $_SESSION['success'] = "Trainer has been successfully updated!";
                                redirect("{$userRole}/trainers/viewTrainer?id={$trainer_id}");
                            } else {
                                $_SESSION['error'] = "There was an issue updating the Trainer. Please try again.";
                                redirect("{$userRole}/trainers/viewTrainer?id={$trainer_id}");
                            }
                        } else {
                            // Validation failed, pass errors and form data
                            $data = [
                                'errors' => $trainerModel->errors,
                                'member' => $_POST
                            ];

                            // Load the appropriate view based on the user role
                            $viewPath = $userRole . '-viewTrainer?id='. $trainer_id; // Create the view path dynamically
                            $this->view("{$userRole}/{$viewPath}", $data); // Dynamic view loading
                        }
                    } else {
                        // Redirect based on the user role
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/trainers');
                                break;
                            case 'Receptionist':
                                redirect('receptionist/trainers');
                                break;
                            case 'Manager':
                                redirect('manager/trainers');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;

                case 'deleteTrainer':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                        $userRole = $_SESSION['role'];
                
                        // Begin the deletion process
                        if (!isset($userId) || empty($userId)) {
                            $_SESSION['error'] = "Invalid Trainer ID.";
                            redirect("{$userRole}/trainers");
                        }

                        // Begin the deletion process
                        if ($userModel->delete($userId, 'user_id')) {
                            // Set success message if the deletion is successful
                            $_SESSION['success'] = "Trainer has been deleted successfully.";
                    
                            // Redirect based on the user's role
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/trainers');
                                    break;
                                case 'Receptionist':
                                    redirect('receptionist/trainers');
                                    break;
                                case 'Manager':
                                    redirect('manager/trainers');
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                        }
                        } else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the Trainer. Please try again.";
                    
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/trainers/viewTrainer?id=' . $userId);
                                    break;
                                case 'Receptionist':
                                    redirect('receptionist/trainers/viewTrainer?id=' . $userId);
                                    break;
                                case 'Manager':
                                    redirect('manager/trainers/viewTrainer?id=' . $userId);
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        }

                    break;                    
            }
        }

        public function receptionist($action = null) {
            switch ($action) {
        
                case 'registerReceptionist':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $receptionist = new M_Receptionist;
                        $user = new M_User;
                        $userRole = $_SESSION['role'];
            
                        if ($receptionist->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
            
                            $temp['receptionist_id'] = match($temp['gender']) {
                                'Male' => 'RT/M/',
                                'Female' => 'RT/F/',
                                default => 'RT/O/',
                            };
            
                            // Get the last member_id from the database
                            $lastId = $receptionist->getLastReceptionistId();
                            $lastReceptionistId = $lastId ? $lastId->id : 0; // Default to 0 if no members exist
                
                            // Get the last 4 digits of the member_id and increment by 1
                            $lastReceptionistOffset = substr($lastReceptionistId, -4);  // Extract last 4 digits
                            $newOffset = str_pad((int)$lastReceptionistOffset + 1, 4, '0', STR_PAD_LEFT); // Increment and pad to 4 digits
                            $temp['receptionist_id'] .= $newOffset; // Append to member_id
                
                            // Set user_id and hash password
                            $temp['user_id'] = $temp['receptionist_id'];
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                            $temp['status'] = 'Active';
                            $temp['id'] = $lastReceptionistId + 1;

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Receptionist/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']);
                                $targetFile = $targetDir . $fileName;
                
                                // Validate file type and size
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Example file types
                                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                    if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max file size: 5MB
                                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                            $temp['image'] = $fileName; // Save filename for database
                                        } else {
                                            $errors['file'] = "Failed to upload the image. Please try again.";
                                        }
                                    } else {
                                        $errors['file'] = "The file is too large. Max size is 5MB.";
                                    }
                                } else {
                                    $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
                                }
                            }

                            if (empty($errors)) {
                                $user->insert($temp);
                                $receptionist->insert($temp);
                
                                // Set success message in session
                                $_SESSION['success'] = "Receptionist has been successfully registered!";
                
                                // Redirect based on the user role
                                $roleRedirectMap = [
                                    'Admin' => 'admin/receptionists',
                                    'Manager' => 'manager/receptionists'
                                ];
                
                                if (isset($roleRedirectMap[$userRole])) {
                                    redirect($roleRedirectMap[$userRole]);
                                } else {
                                    // Handle case where user role is unrecognized
                                    redirect('404');
                                }
                            } else {
                                // Merge validation errors and pass them to the view
                                $data['errors'] = array_merge($user->errors, $receptionist->errors, $errors);
                
                                // Display the relevant view based on the user role
                                $roleViewMap = [
                                    'Admin' => 'admin/admin-createReceptionist',
                                    'Manager' => 'manager/manager-createReceptionist'
                                ];
                
                                $view = $roleViewMap[$userRole] ?? '404';
                                $this->view($view, $data);
                            }
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $receptionist->errors);
                
                            // Display the relevant view based on the user role
                            $roleViewMap = [
                                'Admin' => 'admin/admin-createReceptionist',
                                'Manager' => 'manager/manager-createReceptionist'
                            ];
                
                            $view = $roleViewMap[$userRole] ?? '404';
                            $this->view($view, $data);
                        }
                    }
                    else{
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/receptionists');
                                break;
                            case 'Manager':
                                redirect('manager/receptionists');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;

                case 'updateReceptionist':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the receptionist model
                        $receptionistModel = new M_Receptionist;
                        $receptionist_id = $_POST['receptionist_id'];
                        $userRole = $_SESSION['role'];

                            // Prepare the data to update the receptionist
                            $receptionist = $receptionistModel->findByReceptionistId($receptionist_id);

                            $data = [
                                'first_name'    => $_POST['first_name'] ?? $receptionist->first_name,
                                'last_name'     => $_POST['last_name'] ?? $receptionist->last_name,
                                'NIC_no'        => $_POST['NIC_no'] ?? $receptionist->NIC_no,
                                'date_of_birth' => $_POST['date_of_birth'] ?? $receptionist->date_of_birth,
                                'home_address'  => $_POST['home_address'] ?? $receptionist->home_address,
                                'contact_number'=> $_POST['contact_number'] ?? $receptionist->contact_number,
                                'gender'        => $_POST['gender'] ?? $receptionist->gender,
                                'email_address' => $_POST['email_address'] ?? $receptionist->email_address,
                                'image'         => $receptionist->image // Preserve current image by default
                            ];


                        // Validate the incoming data
                        if ($receptionistModel->validate($data)) {

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Receptionist/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']);
                                $targetFile = $targetDir . $fileName;
                
                                // Validate file type and size
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                    if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max size 5MB
                                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                            $data['image'] = $fileName;
                                        } else {
                                            $errors['file'] = "Failed to upload the file. Please try again.";
                                        }
                                    } else {
                                        $errors['file'] = "The file is too large. Max size is 5MB.";
                                    }
                                } else {
                                    $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF are allowed.";
                                }
                            }

                            // If no image uploaded, leave the 'image' key as null (if not set)
                            if (!isset($data['image'])) {
                                $data['image'] = $receptionist->image; // Preserve the existing image if no new one is uploaded
                            }
                
                
                            // Call the update function if no errors
                            if (empty($errors) && $receptionistModel->update($receptionist_id, $data, 'receptionist_id')) {
                                $_SESSION['success'] = "Receptionist has been successfully updated!";
                                redirect("{$userRole}/receptionists/viewReceptionist?id={$receptionist_id}");
                            } else {
                                $_SESSION['error'] = "There was an issue updating the Receptionist. Please try again.";
                                redirect("{$userRole}/receptionists/viewReceptionist?id={$receptionist_id}");
                            }
                        } else {

                            // If validation fails, pass errors to the view
                            $data = [
                                'errors' => $receptionistModel->errors,
                                'receptionist' => $_POST // Preserve form data for user correction
                            ];
                            // Load the appropriate view based on the user role
                            $viewPath = $userRole . '-viewReceptionist?id='. $receptionist_id; // Create the view path dynamically
                            $this->view("{$userRole}/{$viewPath}", $data); // Dynamic view loading
                        }
                    } else {
                        // Redirect based on the user role
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/receptionists');
                                break;
                            case 'Manager':
                                redirect('manager/receptionists');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;

                case 'deleteReceptionist':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                        $userRole = $_SESSION['role'];

                
                        // Begin the deletion process
                        if (!isset($userId) || empty($userId)) {
                            $_SESSION['error'] = "Invalid Receptionist ID.";
                            redirect("{$userRole}/receptionists");
                        }

                        // Begin the deletion process
                        if ($userModel->delete($userId, 'user_id')) {
                            // Set success message if the deletion is successful
                            $_SESSION['success'] = "Receptionist has been deleted successfully.";
                    
                            // Redirect based on the user's role
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/receptionists');
                                    break;
                                case 'Manager':
                                    redirect('manager/receptionists');
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        } else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the Receptionist. Please try again.";
                    
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/receptionists/viewReceptionist?id=' . $userId);
                                    break;
                                case 'Manager':
                                    redirect('manager/receptionists/viewReceptionist?id=' . $userId);
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        }

                    break; 

                default:
                    redirect('404');
                    break;                   
            }
        }

        public function manager($action = null) {
            switch ($action) {

                case 'registerManager':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $manager = new M_Manager;
                        $user = new M_User;
                        $userRole = $_SESSION['role'];
            
                        if ($manager->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
            
                            $temp['manager_id'] = match($temp['gender']) {
                                'Male' => 'MR/M/',
                                'Female' => 'MR/F/',
                                default => 'MR/O/',
                            };
            
                            // Get the last member_id from the database
                            $lastId = $manager->getLastManagerId();
                            $lastManagerId = $lastId ? $lastId->id : 0; // Default to 0 if no members exist
                
                            // Get the last 4 digits of the member_id and increment by 1
                            $lastManagerOffset = substr($lastManagerId, -4);  // Extract last 4 digits
                            $newOffset = str_pad((int)$lastManagerOffset + 1, 4, '0', STR_PAD_LEFT); // Increment and pad to 4 digits
                            $temp['manager_id'] .= $newOffset; // Append to member_id
                
                            // Set user_id and hash password
                            $temp['user_id'] = $temp['manager_id'];
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                            $temp['status'] = 'Active';
                            $temp['id'] = $lastManagerId + 1;

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Manager/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']);
                                $targetFile = $targetDir . $fileName;
                
                                // Validate file type and size
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Example file types
                                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                    if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max file size: 5MB
                                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                            $temp['image'] = $fileName; // Save filename for database
                                        } else {
                                            $errors['file'] = "Failed to upload the image. Please try again.";
                                        }
                                    } else {
                                        $errors['file'] = "The file is too large. Max size is 5MB.";
                                    }
                                } else {
                                    $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
                                }
                            }

                            if (empty($errors)) {
                                $user->insert($temp);
                                $manager->insert($temp);
                
                                // Set success message in session
                                $_SESSION['success'] = "Manager has been successfully registered!";
                
                                // Redirect based on the user role
                                $roleRedirectMap = [
                                    'Admin' => 'admin/managers',
                                    'Manager' => 'manager/managers'
                                ];
                
                                if (isset($roleRedirectMap[$userRole])) {
                                    redirect($roleRedirectMap[$userRole]);
                                } else {
                                    // Handle case where user role is unrecognized
                                    redirect('404');
                                }
                            } else {
                                // Merge validation errors and pass them to the view
                                $data['errors'] = array_merge($user->errors, $manager->errors, $errors);
                
                                // Display the relevant view based on the user role
                                $roleViewMap = [
                                    'Admin' => 'admin/admin-createManager',
                                    'Manager' => 'manager/manager-createManager'
                                ];
                
                                $view = $roleViewMap[$userRole] ?? '404';
                                $this->view($view, $data);
                            }
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $manager->errors);
                
                            // Display the relevant view based on the user role
                            $roleViewMap = [
                                'Admin' => 'admin/admin-createManager',
                                'Manager' => 'manager/manager-createManager'
                            ];
                
                            $view = $roleViewMap[$userRole] ?? '404';
                            $this->view($view, $data);
                        }
                    }
                    else{
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/managers');
                                break;
                            case 'Manager':
                                redirect('manager/managers');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;

                case 'updateManager':
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the manager model
                        $managerModel = new M_Manager;
                        $userRole = $_SESSION['role'];

                
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
                        if ($managerModel->update($manager_id, $data, 'manager_id')) {
                            // Set a success session message
                            $_SESSION['success'] = "Manager has been successfully updated!";
                            // Redirect to the manager view page
                            redirect("{$userRole}/managers/viewManager?id={$manager_id}");
                        } else {
                            // Handle update failure (optional)
                            $_SESSION['error'] = "There was an issue updating the manager. Please try again.";
                            redirect("{$userRole}/managers/viewManager?id={$manager_id}");
                        }
                
                    } else {
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/receptionists');
                                break;
                            case 'Manager':
                                redirect('manager/receptionists');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;
                    

                case 'deleteManager':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                        $userRole = $_SESSION['role'];
                
                        // Begin the deletion process
                        if (!isset($userId) || empty($userId)) {
                            $_SESSION['error'] = "Invalid Manager ID.";
                            redirect("{$userRole}/managers");
                        }

                        // Begin the deletion process
                        if ($userModel->delete($userId, 'user_id')) {
                            // Set success message if the deletion is successful
                            $_SESSION['success'] = "Manager has been deleted successfully.";
                    
                            // Redirect based on the user's role
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/managers');
                                    break;
                                case 'Manager':
                                    redirect('manager/managers');
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        } else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the Manager. Please try again.";
                    
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/managers/viewManager?id=' . $userId);
                                    break;
                                case 'Manager':
                                    redirect('manager/managers/viewManager?id=' . $userId);
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        }

                    break;         
                
                default:
                    redirect('404');
                    break;
            }
        }

        public function admin($action = null) {
            switch ($action) {
        
                case 'registerAdmin':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $admin = new M_Admin;
                        $user = new M_User;
                        $userRole = $_SESSION['role'];
            
                        if ($admin->validate($_POST) && $user->validate($_POST)) {
                            $temp = $_POST;
            
                            $temp['admin'] = match($temp['gender']) {
                                'Male' => 'AD/M/',
                                'Female' => 'AD/F/',
                                default => 'AD/O/',
                            };
            
                            // Get the last member_id from the database
                            $lastId = $admin->getLastAdminId();
                            $lastAdminId = $lastId ? $lastId->id : 0; // Default to 0 if no members exist
                
                            // Get the last 4 digits of the member_id and increment by 1
                            $lastAdminOffset = substr($lastAdminId, -4);  // Extract last 4 digits
                            $newOffset = str_pad((int)$lastAdminOffset + 1, 4, '0', STR_PAD_LEFT); // Increment and pad to 4 digits
                            $temp['admin_id'] .= $newOffset; // Append to member_id
                
                            // Set user_id and hash password
                            $temp['user_id'] = $temp['admin_id'];
                            $temp['password'] = password_hash($temp['password'], PASSWORD_DEFAULT);
                            $temp['status'] = 'Active';
                            $temp['id'] = $lastAdminId + 1;

                            // Handle file upload if exists
                            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                                $targetDir = "assets/images/Admin/";
                                $fileName = time() . "_" . basename($_FILES['image']['name']);
                                $targetFile = $targetDir . $fileName;
                
                                // Validate file type and size
                                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']; // Example file types
                                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                                    if ($_FILES['image']['size'] <= 5 * 1024 * 1024) { // Max file size: 5MB
                                        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                                            $temp['image'] = $fileName; // Save filename for database
                                        } else {
                                            $errors['file'] = "Failed to upload the image. Please try again.";
                                        }
                                    } else {
                                        $errors['file'] = "The file is too large. Max size is 5MB.";
                                    }
                                } else {
                                    $errors['file'] = "Invalid file type. Only JPEG, PNG, and GIF files are allowed.";
                                }
                            }

                            if (empty($errors)) {
                                $user->insert($temp);
                                $admin->insert($temp);
                
                                // Set success message in session
                                $_SESSION['success'] = "Admin has been successfully registered!";
                
                                // Redirect based on the user role
                                $roleRedirectMap = [
                                    'Admin' => 'admin/admins',
                                    'Manager' => 'manager/admins'
                                ];
                
                                if (isset($roleRedirectMap[$userRole])) {
                                    redirect($roleRedirectMap[$userRole]);
                                } else {
                                    // Handle case where user role is unrecognized
                                    redirect('404');
                                }
                            } else {
                                // Merge validation errors and pass them to the view
                                $data['errors'] = array_merge($user->errors, $admin->errors, $errors);
                
                                // Display the relevant view based on the user role
                                $roleViewMap = [
                                    'Admin' => 'admin/admin-createAdmin',
                                    'Manager' => 'manager/manager-createAdmin'
                                ];
                
                                $view = $roleViewMap[$userRole] ?? '404';
                                $this->view($view, $data);
                            }
                        } else {
                            // Merge validation errors and pass to the view
                            $data['errors'] = array_merge($user->errors, $admin->errors);
                
                            // Display the relevant view based on the user role
                            $roleViewMap = [
                                'Admin' => 'admin/admin-createAdmin',
                                'Manager' => 'manager/manager-createAdmin'
                            ];
                
                            $view = $roleViewMap[$userRole] ?? '404';
                            $this->view($view, $data);
                        }
                    }
                    else{
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/admins');
                                break;
                            case 'Manager':
                                redirect('manager/admins');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;

                case 'updateAdmin':

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        // Initialize the admin model
                        $adminModel = new M_Admin;
                        $userRole = $_SESSION['role'];
                
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
                        if ($adminModel->update($admin_id, $data, 'admin_id')) {
                            // Set a success session message
                            $_SESSION['success'] = "Admin has been successfully updated!";
                            // Redirect to the admin view page
                            redirect("{$userRole}/admins/viewAdmin?id={$admin_id}");
                        } else {
                            // Handle update failure (optional)
                            $_SESSION['error'] = "There was an issue updating the admin. Please try again.";
                            redirect("{$userRole}/admins/viewAdmin?id={$admin_id}");
                        }
                
                    } else {
                        $userRole = $_SESSION['role'];
                        switch ($userRole) {
                            case 'Admin':
                                redirect('admin/admins');
                                break;
                            case 'Manager':
                                redirect('manager/admins');
                                break;
                            default:
                                // Handle case where the role is unrecognized
                                redirect('404');
                                break;
                        }
                    }
                    break;
                    

                case 'deleteAdmin':

                        $userModel = new M_User;
                
                        // Get the user ID from the GET parameters
                        $userId = $_GET['id'];
                        $userRole = $_SESSION['role'];
                
                        // Begin the deletion process
                        if (!isset($userId) || empty($userId)) {
                            $_SESSION['error'] = "Invalid Admin ID.";
                            redirect("{$userRole}/admins");
                        }

                        // Begin the deletion process
                        if ($userModel->delete($userId, 'user_id')) {
                            // Set success message if the deletion is successful
                            $_SESSION['success'] = "Admin has been deleted successfully.";
                    
                            // Redirect based on the user's role
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/admins');
                                    break;
                                case 'Manager':
                                    redirect('manager/admins');
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        } else {
                            // Handle deletion failure
                            $_SESSION['error'] = "There was an issue deleting the Admin. Please try again.";
                    
                            switch ($userRole) {
                                case 'Admin':
                                    redirect('admin/admins/viewAdmin?id=' . $userId);
                                    break;
                                case 'Manager':
                                    redirect('manager/admins/viewAdmin?id=' . $userId);
                                    break;
                                default:
                                    // Handle case where the role is unrecognized
                                    redirect('404');
                                    break;
                            }
                        }


                    break;                    
                                       
        
                default:
                    redirect('404');
                    break;
            }
        }


    }