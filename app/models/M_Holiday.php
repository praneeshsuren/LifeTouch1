<?php

    //Admin class
    class M_Holiday{

        use Model;

        protected $table = 'holiday';
        protected $allowedColumns = [
            'id',
            'date',
            'reason'
        ];

        public function validate($data) {
            $this->errors = [];

            if (empty($data['date'])) {
                $this->errors['slot'] = 'Date is required';
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