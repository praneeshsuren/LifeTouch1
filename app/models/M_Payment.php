<?php

    //Admin class
    class M_Payment{

        use Model;

        protected $table = 'payment';
        protected $allowedColumns = [
            'id',
            'email',
            'package_name',
            'amount',
            'member_id',
            'status',
            'created_at',
            'payment_intent_id'
        ];


        public function paymentMember($member_id){
            $query = "Select * from payment AS p
            WHERE p.member_id = :member_id";

            return $this->query($query, ['member_id' => $member_id]);
        }

        public function paymentAdmin(){
            $query = "SELECT
                p.*,
                m.member_id AS member_id, 
                CONCAT(m.first_name, ' ', m.last_name) AS member_name
             FROM payment AS p
             JOIN member m ON p.member_id = m.member_id";

            return $this->query($query);
        }

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['package_name'])) {
                $this->errors['package_name'] = 'package_name is required';
            } 
            if (empty($data['email'])) {
                $this->errors['email'] = 'email is required';
            } 
            if (empty($data['amount'])) {
                $this->errors['amount'] = 'amount is required';
            } 
            if (empty($data['member_id'])) {
                $this->errors['member_id'] = 'member_id is required';
            } 
            if (empty($data['status'])) {
                $this->errors['status'] = 'status is required';
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