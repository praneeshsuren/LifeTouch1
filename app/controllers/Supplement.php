<?php

    class Supplement extends Controller
    {
        public function create_supplement()
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
    }

?>