<?php

    //Member class
    class M_Member{

        use Model;

        protected $table = 'member';
        protected $allowedColumns = [
            'member_id',
            'first_name',
            'last_name',
            'date_of_birth',
            'NIC_no',
            'home_address',
            'height',
            'weight',
            'contact_number',
            'gender',
            'email_address',
            'status',
            'image',
            'membershipPlan_id'
        ];

        public function findByMemberId($memberId) {
            $data = ['member_id' => $memberId];
            return $this->first($data);  // Use the `first` method to get the first matching record
        }

        public function validate($data){
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
            if (empty($data['membership_plan'])) {
                $this->errors['membership_plan'] = 'Membership Plan is required';
            }

            if (empty($data['height'])) {
                $this->errors['height'] = 'Height is required';
            } elseif ($data['height'] < 0) {
                $this->errors['height'] = 'Height cannot be negative';
            }
            
            if (empty($data['weight'])) {
                $this->errors['weight'] = 'Weight is required';
            } elseif ($data['weight'] < 0) {
                $this->errors['weight'] = 'Weight cannot be negative';
            }
            

            if (empty($data['NIC_no'])) {
                $this->errors['NIC_no'] = 'NIC number is required';
            } elseif (strlen($data['NIC_no']) > 12) {
                $this->errors['NIC_no'] = 'NIC number cannot exceed 12 characters';
            }

            // If there are no errors, return true; otherwise, return false.
            return empty($this->errors);
            
        }

        public function emailExists($email, $excludeId = null) {
            $data = ['email_address' => $email];
        
            // Prepare conditions for excluding the current member
            $data_not = [];
            if ($excludeId) {
                $data_not['member_id'] = $excludeId;
            }
        
            // Use the where function to query the database
            $result = $this->where($data, $data_not, 'email_address');
        
            // If we found any result, it means the email exists and is used by another member
            return !empty($result);
        }

    }