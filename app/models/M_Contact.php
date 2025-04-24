<?php

    //Admin class
    class M_Contact{

        use Model;

        protected $table = 'contact';
        protected $allowedColumns = [
            'id',
            'name',
            'email',
            'msg'
        ];

        public function findInquityById($inquiryId) {
            $data = ['id' => $inquiryId];
            return $this->first($data);
        }

        public function validate($data) {
            $this->errors = [];

            if (empty($data['name'])) {
                $this->errors['name'] = 'name is required';
            } 
            if (empty($data['email'])) {
                $this->errors['email'] = 'email is required';
            } 
            if (empty($data['msg'])) {
                $this->errors['msg'] = 'msg is required';
            } 
            
            // If there are no errors, return true; otherwise, return false.
            return empty($this->errors);
        }

        // Method to get errors after validation
        public function getErrors()
        {
            return $this->errors;
        }      
    }