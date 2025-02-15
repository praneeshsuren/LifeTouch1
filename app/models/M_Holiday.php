<?php

    //Admin class
    class M_Holiday{

        use Model;

        protected $table = 'holiday';
        protected $allowedColumns = [
            'id',
            'date',
            'time_slot_id',
            'trainer_id'
        ];

        public function findHolidays(){
            $query = "SELECT h.*,
                t.slot AS slot
              FROM holiday AS h
              JOIN time_slots as t ON h.time_slot_id = t.id
            ";

            return $this->query($query);
        }

        public function validate($data) {
            $this->errors = [];

            if (empty($data['date'])) {
                $this->errors['slot'] = 'Date is required';
            } 
            if (empty($data['time_slot_id'])) {
                $this->errors['slot'] = 'Time slot is required';
            } 
            if (empty($data['trainer_id'])) {
                $this->errors['slot'] = 'Trainer id is required';
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