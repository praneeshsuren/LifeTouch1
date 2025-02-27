<?php

    //Admin class
    class M_Holiday{

        use Model;

        protected $table = 'holiday';
        protected $allowedColumns = [
            'id',
            'date',
            'timeslot_id',
            'trainer_id'
        ];

        public function findHolidays(){
            $query = "SELECT h.date, 
                GROUP_CONCAT(DISTINCT t.slot ORDER BY t.id SEPARATOR ', ') AS timeslots,
                GROUP_CONCAT(DISTINCT t.id ORDER BY t.id SEPARATOR ', ') AS timeslots_ids,
                GROUP_CONCAT(DISTINCT h.trainer_id ORDER BY h.trainer_id SEPARATOR ', ') AS trainer_ids
                FROM holiday AS h
                JOIN timeslot AS t ON t.id = h.timeslot_id
                GROUP BY h.date;
            ";

            return $this->query($query);
        }

        public function validate($data) {
            $this->errors = [];

            if (empty($data['date'])) {
                $this->errors['slot'] = 'Date is required';
            } 
            if (empty($data['timeslot_id'])) {
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