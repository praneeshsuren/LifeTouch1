<?php

    //Trainer class
    class M_Trainer{

        use Model;

        protected $table = 'trainer';
        protected $allowedColumns = [
            'trainer_id',
            'first_name',
            'last_name',
            'NIC_no',
            'date_of_birth',
            'home_address',
            'contact_number',
            'gender',
            'email_address',
            'image',
            'status'
        ];

        public function findByTrainerId($trainerId) {
            $data = ['trainer_id' => $trainerId];
            return $this->first($data);  // Use the `first` method to get the first matching record
        }

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['first_name'])) {
                $this->errors['first_name'] = 'First name is required';
            }
        
            if (empty($data['last_name'])) {
                $this->errors['last_name'] = 'Last name is required';
            }
        
            if (empty($data['date_of_birth'])) {
                $this->errors['date_of_birth'] = 'Date of birth is required';
            }
        
            if (empty($data['home_address'])) {
                $this->errors['home_address'] = 'Home address is required';
            }
        
            if (empty($data['contact_number'])) {
                $this->errors['contact_number'] = 'Contact number is required';
            }
        
            if (empty($data['email_address'])) {
                $this->errors['email_address'] = 'Email address is required';
            } else {
                if (!filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
                    $this->errors['email_address'] = 'Invalid email address';
                }
            }

            if (empty($data['gender'])) {
                $this->errors['gender'] = 'Gender is required';
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                    $this->errors['image'] = "Image upload failed. Please try again.";
                } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) { // Check if file size exceeds 2MB
                    $this->errors['image'] = "The image size must be less than 2MB.";
                } else {
                    $allowed = ['jpg', 'jpeg', 'png', 'gif']; // Allowed file extensions
                    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    if (!in_array($ext, $allowed)) {
                        $this->errors['image'] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
                    }
                }
            } else {
                // No file uploaded (optional, depending on requirements)
                // Uncomment the line below if you want to enforce image upload
                // $this->errors['image'] = "Image upload is required.";
            }
            

            // If there are no errors, return true; otherwise, return false.
            return empty($this->errors);
        }
      
    }