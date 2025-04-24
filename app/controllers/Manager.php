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

        // Query to group equipment by name and count them
        $inventoryCounts = $equipmentModel->query("
        SELECT 
            REGEXP_REPLACE(name, '[0-9]+$', '') as base_name, 
            COUNT(*) as count 
            FROM equipment 
            GROUP BY base_name
        ");
        // Query to count each membership plan
        $membershipCounts = $memberModel->query("SELECT membership_plan, COUNT(*) as count 
        FROM member 
        GROUP BY membership_plan");

        // Fetch the latest 4 announcements with admin names
        $announcements = $announcementModel->findAllWithAdminNames(4);

        $data = [
            'announcements' => $announcements,
            'membershipCounts' => $membershipCounts,
            'inventoryCounts' => $inventoryCounts,
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
                $this->view('manager/member_create');
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
                $memberModel = new M_Member;
                $member = $memberModel->findByMemberId($_GET['id']);

                $data = [
                    'member' => $member
                ];

                $this->view('manager/member_view', $data);
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
                            redirect('admin/members/viewMember?id=' . $member_id);
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
                } else {
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

    public function member()
    {
        $this->view('manager/member');
    }
    public function member_view()
    {
        $this->view('manager/member_view');
    }
    public function member_edit()
    {
        $this->view('manager/member_edit');
    }
    public function member_create($action = null)
    {
        switch ($action) {
            case 'createMember':
                // Load the form view to create a member
                $this->view('manager/member_create');
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

        $this->view('manager/member_create');
    }
    public function trainer()
    {
        $this->view('manager/trainer');
    }
    public function trainer_create()
    {
        $this->view('manager/trainer_create');
    }
    public function trainer_view()
    {
        $this->view('manager/trainer_view');
    }
    public function admin()
    {
        $this->view('manager/admin');
    }
    public function admin_create()
    {
        $this->view('manager/admin_create');
    }
    public function admin_view()
    {
        $this->view('manager/admin_view');
    }
    public function service_edit()
    {
        $this->view('manager/service_edit');
    }
    public function equipment()
    {
        $equipmentModel = new M_Equipment(); // Assume this is your equipment model
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
            'equipment' => $equipment[0],  // Assuming it's an array, or adjust if it's an object
            'services' => $services
        ]);
    }


    public function equipment_delete($id)
    {
        $equipmentModel = new M_Equipment();  // Create an instance of the M_Equipment model

        // Call the delete method from the model
        $result = $equipmentModel->delete($id, 'equipment_id');  // 'equipment_id' is the column to identify the equipment

        if ($result === false) {
            // Handle failure (e.g., redirect to the equipment list with a failure message)
            $_SESSION['message'] = 'Failed to delete equipment.';
        } else {
            // Handle success (e.g., redirect to the equipment list with a success message)
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
                'plan' => trim($_POST['plan_name']),
                'amount' => (float) trim($_POST['amount'])  // Cast to float
            ];

            if ($membershipModel->validate($planData)) {
                if ($membershipModel->insert($planData)) {
                    $_SESSION['success'] = "Plan created successfully!";
                } else {
                    $_SESSION['error'] = "Failed to add plan.";
                }
            } else {
                $_SESSION['form_errors'] = $membershipModel->getErrors();
                $_SESSION['form_data'] = $_POST;
            }

            redirect('manager/membership_plan');
        } else {
            redirect('manager/membership_plan');
        }
    }

    public function delete_plan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $planId = $_POST['membershipPlan_id'];

            $model = new M_Membership_plan();
            $model->delete($planId, 'membershipPlan_id'); // Specify the primary key column

            redirect('manager/membership_plan');
        }
    }
    public function update_plan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = new M_Membership_plan();
            $id = $_POST['membershipPlan_id'];

            if ($model->validate($_POST)) {
                $model->update($id, $_POST, 'membershipPlan_id');
                redirect('manager/membership_plan');
            } else {
                // Reload membership plans to keep the table visible
                $data = [
                    'membership_plan' => $model->findAll(),
                    'edit_data' => $_POST,
                    'edit_errors' => $model->getErrors()
                ];

                $_SESSION['edit_data'] = $_POST;
                $_SESSION['edit_errors'] = $model->getErrors();
                $_SESSION['error'] = "Please fix the highlighted errors.";

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
}
