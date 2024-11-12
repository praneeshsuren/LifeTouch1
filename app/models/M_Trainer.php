<?php

    //Trainer class
    class M_Trainer{

        use Model;

        protected $table = 'trainer';
        protected $allowedColumns = [
            'trainer_id',
            'first_name',
            'last_name',
            'full_name',
            'date_of_birth',
            'age',
            'address',
            'contact_number',
            'gender',
            'email'
        ];

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
        
            if (empty($data['address'])) {
                $this->errors['address'] = 'Address is required';
            }
        
            if (empty($data['contact_number'])) {
                $this->errors['contact_number'] = 'Contact number is required';
            }
        
            if (empty($data['email'])) {
                $this->errors['email'] = 'Email address is required';
            } else {
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->errors['email'] = 'Invalid email address';
                }
            }
        
            if (empty($data['gender'])) {
                $this->errors['gender'] = 'Gender is required';
            }
        
            // If there are no errors, return true; otherwise, return false.
            return empty($this->errors);
        }
        

    }