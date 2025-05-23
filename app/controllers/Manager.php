<?php

class Manager extends Controller
{

    public function __construct()
    {
        // Check if the user is logged in as a manager
        $this->checkAuth('Manager');
    }

    public function index()
    {
        $memberModel = new M_Member();
        $equipmentModel = new M_Equipment();
        $announcementModel = new M_Announcement;
        $supplementModel = new M_Supplements();

        // Query to group equipment by name and count them
        $inventoryCounts = $equipmentModel->query("
        SELECT 
            REGEXP_REPLACE(name, '[0-9]+$', '') as base_name, 
            COUNT(*) as count 
            FROM equipment 
            GROUP BY base_name
        ");

        // Fetch the latest 4 announcements with admin names
        $announcements = $announcementModel->findAllWithAdminNames(4);
        $members = $memberModel->countAll();
        $recentMembers = $memberModel->countRecentMembers();
        $supplementCount = $supplementModel->countAllSupplements();
        $equipmentCount = $equipmentModel->countAllEquipment();

        $data = [
            'announcements' => $announcements,
            'inventoryCounts' => $inventoryCounts,
            'members' => $members,
            'recentMembers' => $recentMembers,
            'supplementCount' => $supplementCount,
            'equipmentCount' => $equipmentCount
        ];
        
        $this->view('manager/manager_dashboard', $data);
    }


    public function announcement()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $announcement = new M_Announcement;
            if ($announcement->validate($_POST)) {
                $temp = $_POST;

                $temp['announcement_id'] = 'A';
                $offset = str_pad($announcement->countAll() + 1, 4, '0', STR_PAD_LEFT);
                $temp['announcement_id'] .= $offset;

                $temp['created_by'] = $_SESSION['user_id'];

                $announcement->insert($temp);
                $_SESSION['success'] = "Announcement has been successfully published!";
                redirect('manager/announcement_main');
            } else {
                $data['errors'] = $announcement->errors;
                $this->view('manager/announcement', $data);
            }
        } else {
            $this->view('manager/announcement');
        }
    }

    public function announcement_main()
    {
        $announcementModel = new M_Announcement;
        $announcements = $announcementModel->findAllWithAdminDetails();

        $data = [
            'announcements' => $announcements
        ];

        $this->view('manager/announcement_main', $data);
    }

    public function announcement_update()
    {
        $this->view('manager/announcement_update');
    }
    public function announcement_read($id)
    {
        $this->view('manager/announcement_read');
    }
    public function delete_announcement($id)
    {
        // Load the Announcement model
        $announcement = new M_Announcement();

        // Call the model's delete method with the correct column name
        if ($announcement->delete($id, 'announcement_id')) {
            // Redirect to the announcements page with a success message
            redirect('manager/announcement_main');
        } else {
            // Redirect to the announcements page with an error message
            redirect('manager/announcement_main?error=delete_failed');
        }
    }


    public function report()
    {
        $this->view('manager/report');
    }


    public function report_main()
    {
        $this->view('manager/report_main');
    }

    public function members($action = null)
    {
        switch ($action) {
            case 'createMember':
                // Load the form view to create a member
                $this->view('manager/manager-createMember');
                break;

            case 'registerMember':
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
                        redirect('manager/member');
                    } else {
                        // Merge validation errors and pass to the view
                        $data['errors'] = array_merge($user->errors, $member->errors);
                        $this->view('manager/member_create', $data);
                    }
                } else {
                    redirect('manager/member');
                }

                break;

            case 'viewMember':
                // Load the view to view a trainer
                $member_id = $_GET['id'];

                $memberModel = new M_Member;
                $member = $memberModel->findByMemberId($member_id);

                $membershipSubscriptionModel = new M_MembershipSubscriptions;
                $membershipSubscription = $membershipSubscriptionModel->findByMemberId($member_id);

                $data = [
                    'member' => $member,
                    'membershipSubscription' => $membershipSubscription
                ];

                $this->view('manager/manager-viewMember', $data);
                break;

            case 'memberSupplements':
                $memberId = $_GET['id'];

                if ($memberId) {

                    $supplementSalesModel = new M_SupplementSales;

                    // Fetch the supplement records for the member
                    $supplementRecords = $supplementSalesModel->findByMemberId($memberId);

                    $data = [
                        'supplements' => $supplementRecords
                    ];

                    $this->view('manager/manager-memberSupplements', $data);
                } else {
                    $_SESSION['error'] = 'Member not found.';
                    redirect('manager/members');
                }
                break;

            case 'memberAttendance':
                $this->view('manager/manager-memberAttendance');
                break;

            case 'memberPaymentHistory':
                $this->view('manager/manager-memberPaymentHistory');
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
                            'contact_number' => $_POST['contact_number'],
                            'gender'        => $_POST['gender'],
                            'email_address' => $_POST['email_address'],
                            'image'         => $_POST['image']
                        ];

                        $member_id = $_POST['member_id'];

                        // Call the update function
                        if (!$memberModel->update($member_id, $data, 'member_id')) {
                            // Set a success session message
                            $_SESSION['success'] = "Member has been successfully updated!";
                            // Redirect to the trainer view page
                            redirect('manager/members/viewMember?id=' . $member_id);
                        } else {
                            // Handle update failure (optional)
                            $_SESSION['error'] = "There was an issue updating the member. Please try again.";
                            redirect('manager/members/viewMember?id=' . $member_id);
                        }
                    } else {
                        // If validation fails, pass errors to the view
                        $data = [
                            'errors' => $memberModel->errors,
                            'member' => $_POST // Preserve form data for user correction
                        ];
                        // Render the view with errors and form data
                        $this->view('manager/manager-viewMember', $data);
                    }
                } else {
                    // Redirect if the request is not a POST request
                    redirect('manager/members');
                }
                break;

            case 'deleteMember':

                $userModel = new M_User;

                // Get the user ID from the GET parameters
                $userId = $_GET['id'];

                // Begin the deletion process
                if (!$userModel->delete($userId, 'user_id')) {

                    $_SESSION['success'] = "Member has been deleted successfully";

                    redirect('manager/members');
                } else {
                    // Handle deletion failure
                    $_SESSION['error'] = "There was an issue deleting the member. Please try again.";
                    redirect('manager/members/viewMember?id=' . $userId);
                }

                break;

            default:
                // Fetch all members and pass to the view
                $memberModel = new M_Member;
                $members = $memberModel->findAll('created_at');

                $data = [
                    'members' => $members
                ];

                $this->view('manager/manager-members', $data);
                break;
        }
    }

    public function trainers($action = null) {
        switch ($action) {
            case 'createTrainer':
                // Load the form view to create a trainer
                $this->view('manager/manager-createTrainer');
                break;
    
            
            case 'viewTrainer':
                // Load the view to view a trainer
                $trainerModel = new M_Trainer;
                $trainer = $trainerModel->findByTrainerId($_GET['id']);
    
                $data = [
                    'trainer' => $trainer
                ];
    
                $this->view('manager/manager-viewTrainer', $data);
                break;
            
            case 'salaryHistory':

                $trainer_id = $_GET['id'];

                $this->view('manager/manager-trainerSalaryHistory');
                break;

            case 'trainerCalendar':

                $trainer_id = $_GET['id'];

                $this->view('manager/manager-trainerCalendar');
                break;
            
            default:
                // Fetch all trainers and pass to the view
                $trainerModel = new M_Trainer;
                $trainers = $trainerModel->findAll('created_at');
    
                $data = [
                    'trainers' => $trainers
                ];
    
                $this->view('manager/manager-trainers', $data);
                break;
        }
    }
    
    public function admins($action = null) {
        switch ($action) {
            case 'createAdmin':
                // Load the form view to create a admin
                $this->view('manager/manager-createAdmin');
                break;

            case 'viewAdmin':
                // Load the view to view a admin
                $adminModel = new M_Admin;
                $admin = $adminModel->findByAdminId($_GET['id']);
    
                $data = [
                    'admin' => $admin
                ];
    
                $this->view('manager/manager-viewAdmin', $data);
                break;
                
            case 'salaryHistory':
                
                $admin_id = $_GET['id'];

                $this->view('manager/manager-adminSalaryHistory');
                break;
    
            default:
                // Fetch all admins and pass to the view
                $adminModel = new M_Admin;
                $admins = $adminModel->findAll('created_at');

                $data = [
                    'admins' => $admins
                ];

                $this->view('manager/manager-admins', $data);
                break; 
        }
    }
    public function service_edit()
    {
        $this->view('manager/service_edit');
    }
    public function equipment()
    {
        $equipmentModel = new M_Equipment();
        $data['equipment'] = $equipmentModel->findAll(); // Fetch all equipment data
        $this->view('manager/equipment', $data);
    }
    public function equipment_create()
    {
        $errors = []; // Initialize errors array

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $equipment = new M_Equipment;

            // Combine POST data with uploaded file details
            $data = $_POST;

            // Handle file upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $targetDir = "assets/images/Equipment/";
                $fileName = time() . "_" . basename($_FILES['image']['name']); // Unique filename
                $targetFile = $targetDir . $fileName;

                // Validate the file and move it to the target directory
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $data['file'] = $fileName; // Save the filename for the database
                } else {
                    $errors['file'] = "Failed to upload the file. Please try again.";
                }
            } else {
                $errors['file'] = "Image file is required.";
            }

            // Validate the rest of the data
            if ($equipment->validate($data) && empty($errors)) {
                // Save the data to the database
                $equipment->insert($data);

                // Redirect to the equipment list page
                redirect('manager/equipment');
            } else {
                // Merge validation errors with file upload errors
                $errors = array_merge($errors, $equipment->getErrors());
            }
        }

        // Load the form view with errors (if any)
        $this->view('manager/equipment_create', ['errors' => $errors]);
    }

    public function equipment_view($id)
    {
        // Create an instance of the M_Equipment model
        $equipmentModel = new M_Equipment();

        // Fetch the equipment record by ID, limit the result to 1
        $equipment = $equipmentModel->where(['equipment_id' => $id], [], 1);

        // Check if the equipment exists
        if (!$equipment) {
            // Redirect to the equipment list if no record found
            redirect('manager/equipment');
            return;
        }

        // Fetch the service history for the given equipment ID
        $serviceModel = new M_Service();
        $services = $serviceModel->where(['equipment_id' => $id], [], 1);

        // Pass both the equipment data and service history to the view
        $this->view('manager/equipment_view', [
            'equipment' => $equipment[0],  
            'services' => $services
        ]);
    }


    public function equipment_delete($id)
    {
        $equipmentModel = new M_Equipment();  // Create an instance of the M_Equipment model

        // Call the delete method from the model
        $result = $equipmentModel->delete($id, 'equipment_id');  // 'equipment_id' is the column to identify the equipment

        if ($result === false) {
            $_SESSION['message'] = 'Failed to delete equipment.';
        } else {
            $_SESSION['message'] = 'Equipment deleted successfully.';
        }

        // Redirect back to the equipment list
        redirect('manager/equipment');
    }
    public function equipment_edit($id)
    {
        $equipmentModel = new M_Equipment();

        // Validate the equipment ID
        if (empty($id) || !is_numeric($id)) {
            $_SESSION['message'] = "Invalid equipment ID.";
            redirect('manager/equipment');
        }

        // Fetch the existing equipment details by ID
        $equipment = $equipmentModel->where(['equipment_id' => $id], [], 1);

        if (!$equipment) {
            // If no equipment found, redirect to the equipment list
            redirect('manager/equipment');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect form data
            $updatedData = $_POST;

            // Handle file upload if a new file is provided
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                $targetDir = "assets/images/Equipment/";
                $fileName = time() . "_" . basename($_FILES["file"]["name"]);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                    $updatedData['file'] = $fileName;
                } else {
                    $_SESSION['message'] = "Failed to upload image.";
                    $this->view('manager/equipment_edit', ['equipment' => $equipment[0]]);
                    return;
                }
            }

            // Validate the data using the model's validate function
            if ($equipmentModel->validate($updatedData)) {
                // Update the equipment in the database
                $updateResult = $equipmentModel->update($id, $updatedData, 'equipment_id');

                if ($updateResult === false) {
                    $_SESSION['message'] = "Failed to update equipment.";
                } else {
                    $_SESSION['message'] = "Equipment updated successfully.";
                }

                // Redirect to the equipment list page after updating
                redirect('manager/equipment');
            } else {
                // Pass validation errors to the view
                $this->view('manager/equipment_edit', [
                    'equipment' => $equipment[0],
                    'errors' => $equipmentModel->getErrors()
                ]);
                return;
            }
        }

        // Pass the equipment data to the view for editing
        $this->view('manager/equipment_edit', ['equipment' => $equipment[0]]);
    }

    public function membership_plan()
    {
        $membershipModel = new M_Membership_plan();
        $data['membership_plan'] = $membershipModel->findAll();
        $this->view('manager/membership_plan', $data);
    }

    public function create_plan()
    {
        $membershipModel = new M_Membership_plan();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $planData = [
                'plan' => trim($_POST['plan']),
                'duration' => trim($_POST['duration']),
                'amount' => trim($_POST['amount'])
            ];

            if ($membershipModel->validate($planData)) {
                if ($membershipModel->insert($planData)) {
                    $_SESSION['success'] = "Plan created successfully!";
                } else {
                    $_SESSION['error'] = "Failed to add plan.";
                }
                redirect('manager/membership_plan');
            } else {
                $_SESSION['form_errors'] = $membershipModel->getErrors();
                $_SESSION['form_data'] = $_POST;
                redirect('manager/membership_plan');
            }
        } else {
            redirect('manager/membership_plan');
        }
    }

    public function delete_plan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $planId = $_POST['membershipPlan_id'];
            $model = new M_Membership_plan();

            if ($model->delete($planId, 'membershipPlan_id')) {
                $_SESSION['success'] = "Plan deleted successfully";
            } else {
                $_SESSION['error'] = "Failed to delete plan";
            }

            redirect('manager/membership_plan');
        }
    }

    public function update_plan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = new M_Membership_plan();
            $id = $_POST['membershipPlan_id'];

            $data = [
                'plan' => trim($_POST['plan']),
                'duration' => trim($_POST['duration']),
                'amount' => trim($_POST['amount'])
            ];

            if ($model->validate($data)) {
                if ($model->update($id, $data, 'membershipPlan_id')) {
                    $_SESSION['success'] = "Plan updated successfully";
                } else {
                    $_SESSION['error'] = "Failed to update plan";
                }
                redirect('manager/membership_plan');
            } else {
                $_SESSION['edit_data'] = $_POST;
                $_SESSION['edit_errors'] = $model->getErrors();
                redirect('manager/membership_plan');
            }
        }
    }


    public function supplements()
    {
        $supplementsModel = new M_Supplements; // Assume this is your equipment model
        $data['supplement'] = $supplementsModel->findAll(); // Fetch all equipment data
        $this->view('manager/manager-supplement', $data);
    }

    public function createSupplement()
    {
        $this->view('manager/manager-createSupplement');
    }

    public function supplement_view($id)
    {
        // Create an instance of the M_Supplements model
        $supplementModel = new M_Supplements();

        // Fetch the supplement record by ID, limit the result to 1
        $supplement = $supplementModel->where(['supplement_id' => $id], [], 1);

        // Check if the supplement exists
        if (!$supplement) {
            // Redirect to the supplement list if no record found
            redirect('manager/supplements');
            return;
        }

        $purchasesModel = new M_SupplementPurchases();

        $purchases = $purchasesModel->where(['supplement_id' => $id], [], 1);

        // Pass the supplement data to the view
        $this->view('manager/manager-viewSupplement', [
            'supplement' => $supplement[0],
            'purchases' => $purchases
        ]);
    }

    public function notifications(){
        // Assuming the user ID is stored in session
        $userId = $_SESSION['user_id'];

        // Fetch notifications from the Notification model
        $notificationModel = new M_Notification();
        $notifications = $notificationModel->getNotifications($userId);

        // Pass notifications to the view
        $data['notifications'] = $notifications;

        // Load the notifications view
        $this->view('manager/manager-notifications', $data);
    }

    public function settings(){
            
        $user_id = $_SESSION['user_id'];
        $managerModel = new M_Manager;
        $userModel = new M_User;
        $user = $userModel->findByUserId($user_id);
        $manager = $managerModel->findByManagerId($user_id);
        $data = [
            'manager' => $manager,
            'user' => $user
        ];

        $this->view('manager/manager-settings', $data);
    }

    public function updateSettings() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $managerModel = new M_Manager;
            $userModel = new M_User;
    
            // Sanitize inputs
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
            // Retrieve existing admin data to compare
            $manager_id = $_POST['user_id'];
            $existingManager = $managerModel->findByManagerId($manager_id);
    
            // Retrieve existing user data to compare (for username check)
            $existingUser = $userModel->findByUserId($manager_id); // Assuming findByUserId exists for users table
    
            // Initialize data array to track changes
            $data = [];
    
            // Only include fields that have been updated
            $fields = ['first_name', 'last_name', 'NIC_no', 'date_of_birth', 'home_address', 'contact_number', 'email_address', 'image'];
    
            // Check for changes and add them to the data array
            foreach ($fields as $field) {
                if (isset($_POST[$field]) && $_POST[$field] !== $existingManager->$field) {
                    $data[$field] = $_POST[$field];
                }
            }
    
            // Handle email uniqueness check manually if it's updated
            if (isset($_POST['email_address']) && $_POST['email_address'] !== $existingManager->email_address) {
                if ($managerModel->emailExists($_POST['email_address'], $manager_id)) {
                    $_SESSION['error'] = "Email is already in use.";
                    $data = [
                        'errors' => ['email_address' => 'Email is already in use.'],
                        'manager' => $_POST
                    ];
                    $this->view('manager/manager-settings', $data);
                    return; // Prevent further execution if email is already in use
                }
            }
    
            // Check if the username has changed
            if (isset($_POST['username']) && $_POST['username'] !== $existingUser->username) {
                if ($userModel->usernameExists($_POST['username'])) {
                    $_SESSION['error'] = "Username is already taken.";
                    $data = [
                        'errors' => ['username' => 'Username is already in use.'],
                        'admin' => $_POST
                    ];
                    $this->view('manager/manager-settings', $data);
                    return; // Prevent further execution if username is already in use
                } else {
                    $data['username'] = $_POST['username'];
                }
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
    
            // Only proceed with the update if data exists
            if (!empty($data)) {


                // Update admin data with the updated values
                $updatedManager = $managerModel->update($manager_id, $data, 'manager_id');
    
                // Update user data (if username was changed)
                if (isset($data['username'])) {
                    $updatedUser = $userModel->update($manager_id, ['username' => $data['username']], 'user_id');
                }
    
                // Check if the updates were successful
                if ($updatedManager && (isset($updatedUser) ? !$updatedUser : true)) {
                    $_SESSION['success'] = "Settings have been successfully updated!";
                } else {
                    $_SESSION['error'] = "No changes detected or update failed.";
                }
    
                redirect('manager/settings');
            } else {
                // If no changes, redirect back
                $_SESSION['error'] = "No changes were made.";
                redirect('manager/settings');
            }
        } else {
            redirect('manager/settings');
        }
    }
}
