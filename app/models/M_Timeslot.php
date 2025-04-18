<?php

    //Admin class
    class M_Timeslot{

        use Model;

        protected $table = 'timeslot';
        protected $allowedColumns = [
            'id',
            'slot'
        ];


        public function findAllId(){
            $query = "SELECT id FROM timeslot";
            return $this->query($query);
        }

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['slot'])) {
                $this->errors['slot'] = 'Time slot is required';
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