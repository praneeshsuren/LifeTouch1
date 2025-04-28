<?php

    //Admin class
    class M_EventPayment{

        use Model;

        protected $table = 'eventPayment';
        protected $allowedColumns = [
            'id',
            'name',
            'nic',
            'event_id',
            'member_id'
        ];

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['event_id'])) {
                $this->errors['event_id'] = 'event_id is required';
            } 
            if (!empty($data['is_member']) && empty($data['member_id'])) {
                $this->errors['member_id'] = 'Member ID is required for members';
            }
            if (empty($data['name'])) {
                $this->errors['name'] = 'name is required';
            } 
            if (empty($data['nic'])) {
                $this->errors['nic'] = 'nic is required';
            } 

            return empty($this->errors);
        }

        public function getErrors()
        {
            return $this->errors;
        }      
    }