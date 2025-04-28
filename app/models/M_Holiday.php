<?php

    //Admin class
    class M_Holiday{

        use Model;

        protected $table = 'holiday';
        protected $allowedColumns = [
            'id',
            'date',
            'slot',
            'trainer_id'
        ];

        public function getHolidaysByTrainerId($trainerId) {
            $query = "SELECT * FROM $this->table WHERE trainer_id = :trainer_id";
            $data = ['trainer_id' => $trainerId];
            return $this->query($query, $data);
        }

        public function validate($data) {
            $this->errors = [];

            if (empty($data['date'])) {
                $this->errors['date'] = 'Date is required';
            } 
            if (empty($data['trainer_id'])) {
                $this->errors['trainer_id'] = 'trainer_id is required';
            } 
            if (empty($data['slot'])) {
                $this->errors['slot'] = 'slot is required';
            } 
            return empty($this->errors);
        }

        public function getErrors()
        {
            return $this->errors;
        }      
    }