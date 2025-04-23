<?php

    //Admin class
    class M_Subscription{

        use Model;

        protected $table = 'membership_subscription';
        protected $allowedColumns = [
            'id',
            'member_id',
            'plan_id',
            'status',
            'start_date',
            'end_date',
        ];


        public function subscriptionMember($member_id) {
            $query = "Select s.* from $this->table AS s
            WHERE s.member_id = :member_id";

            return $this->query($query, ['member_id' => $member_id]);
        }

        public function deactivateExpiredSubscriptions() {
            $today = date('Y-m-d');
            $query = "UPDATE $this->table SET status = 'inactive'
            WHERE end_date < :today AND status = 'active'";
            
            return $this->query($query, ['today' => $today]);
        }

        // public function paymentAdmin(){
        //     $query = "SELECT
        //         p.*,
        //         m.member_id AS member_id, 
        //         CONCAT(m.first_name, ' ', m.last_name) AS member_name
        //      FROM payment AS p
        //      JOIN member m ON p.member_id = m.member_id";

        //     return $this->query($query);
        // }

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['plan_id'])) {
                $this->errors['plan_id'] = 'plan_id is required';
            } 
            if (empty($data['type'])) {
                $this->errors['type'] = 'type is required';
            } 
            if (empty($data['start_date'])) {
                $this->errors['start_date'] = 'start_date is required';
            } 
            if (empty($data['end_date'])) {
                $this->errors['end_date'] = 'end_date is required';
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