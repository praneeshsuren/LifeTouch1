<?php

    //Admin class
    class M_Timeslot{

        use Model;

        protected $table = 'timeslot';
        protected $allowedColumns = [
            'id',
            'slot',
            'trainer_id'
        ];


        public function getTimeslotsByTrainerId($trainerId) {
            $query = "SELECT * FROM $this->table WHERE trainer_id = :trainer_id";
            $data = ['trainer_id' => $trainerId];
            return $this->query($query, $data);
        }

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['slot'])) {
                $this->errors['slot'] = 'Time slot is required';
            } 
            if (empty($data['trainer_id'])) {
                $this->errors['trainer_id'] = 'trainer_id is required';
            } 

            return empty($this->errors);
        }


        public function getErrors()
        {
            return $this->errors;
        }      
    }