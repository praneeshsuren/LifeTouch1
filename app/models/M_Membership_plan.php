<?php

    //Admin class
    class M_Membership_plan{

        use Model;

        protected $table = 'membership_plan';
        protected $allowedColumns = [
            'id',
            'plan',
            'amount',
            'duration'
        ];

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['id'])) {
                $this->errors['membershipPlan_id'] = 'id is required';
            } 
            if (empty($data['plan'])) {
                $this->errors['plan'] = 'plan is required';
            } 
            if (empty($data['duration'])) {
                $this->errors['duration'] = 'duration is required';
            } 
            if (empty($data['amount'])) {
                $this->errors['amount'] = 'amount is required';
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