<?php

class Manager extends Controller
{

    public function __construct() {
        // Check if the user is logged in as a manager
        $this->checkAuth('manager');
    }

    public function index()
    {
        $this->view('manager/manager_dashboard');
    }

    public function announcement()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $announcement = new M_Announcement;
            if ($announcement->validate($_POST)) {

                $announcement->insert($_POST);
                redirect('manager/announcement_main');
            }
            $data['errors'] = $announcement->errors;
        }


        $this->view('manager/announcement');
    }

    public function announcement_main()
    {
        $announcement = new M_Announcement();

        // Fetch all announcements
        $data = $announcement->findAll();
        $this->view('manager/announcement_main', ['data' => $data]);
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
    public function member_create()
    {
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
        $equipmentModel = new M_Equipment(); // Create an instance of the M_Equipment model

        // Fetch the equipment record by ID, limit the result to 1
        $equipment = $equipmentModel->where(['equipment_id' => $id], [], 1);

        // Check if the equipment exists
        if (!$equipment) {
            // Redirect to the equipment list if no record found
            redirect('manager/equipment');
            return;
        }

        // Pass the first item of the equipment result to the view
        $this->view('manager/equipment_view', ['equipment' => $equipment[0]]);
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

        // Fetch the existing equipment details by ID
        $data = ['equipment_id' => $id];
        $equipment = $equipmentModel->where(['equipment_id' => $id], [], 1);

        if (!$equipment) {
            // If no equipment found, redirect to the equipment list
            redirect('manager/equipment');
        }

        // Process form submission when the request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect the form data
            $updatedData = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],

            ];

            // Check if the user has uploaded a new file
            if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
                // Handle the file upload
                //$targetDir = "assets/images/Equipment/";
                $targetFile = basename($_FILES["file"]["name"]);

                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                    // Update the file path in the database
                    $updatedData['file'] = $targetFile;
                } else {
                    // Handle error if file upload fails
                    $_SESSION['message'] = "Failed to upload image.";
                    redirect('manager/equipment_edit/' . $id);
                }
            }

            // Update the equipment in the database
            $updateResult = $equipmentModel->update($id, $updatedData, 'equipment_id');

            if ($updateResult === false) {
                $_SESSION['message'] = "Failed to update equipment.";
            } else {
                $_SESSION['message'] = "Equipment updated successfully.";
            }

            // Redirect to the equipment list page after updating
            redirect('manager/equipment');
        }

        // Pass the equipment data to the view for editing
        $this->view('manager/equipment_edit', ['equipment' => $equipment[0]]);
    }
}
