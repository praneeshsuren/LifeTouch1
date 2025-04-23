<?php

    //Admin class
    class M_EventPayment{

        use Model;

        protected $table = 'eventPayment';
        protected $allowedColumns = [
            'id',
            'name',
            'nic',
            'payment_intent_id',
            'status',
            'event_id',
            'number',
            'member_id'
        ];

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['event_id'])) {
                $this->errors['event_id'] = 'event_id is required';
            } 
            if (empty($data['member_id'])) {
                $this->errors['member_id'] = 'member_id is required';
            } 
            if (empty($data['name'])) {
                $this->errors['name'] = 'name is required';
            } 
            if (empty($data['nic'])) {
                $this->errors['nic'] = 'nic is required';
            } 
            if (empty($data['number'])) {
                $this->errors['number'] = 'number is required';
            } 
            if (empty($data['payment_intent_id'])) {
                $this->errors['payment_intent_id'] = 'payment_intent_id is required';
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