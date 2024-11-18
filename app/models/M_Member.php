<?php

    //Member class
    class M_Member{

        use Model;

        protected $table = 'member';
        protected $allowedColumns = [
            'member_id',
            'first_name',
            'last_name',
            'full_name',
            'date_of_birth',
            'age',
            'address',
            'height',
            'weight',
            'bmi_value',
            'contact_number',
            'gender',
            'email'
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

            if (empty($data['address'])) {
                $this->errors['address'] = 'Address is required';
            }

            if (empty($data['contact_number'])) {
                $this->errors['contact_number'] = 'Contact number is required';
            }

            if (empty($data['email'])) {
                $this->errors['email'] = 'Email is required';
            } else {
                if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $this->errors['email'] = 'Invalid email address';
                }
            }

            
        }

    }