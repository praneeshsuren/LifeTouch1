<?php

    //Admin class
    class M_Booking{

        use Model;

        protected $table = 'booking';
        protected $allowedColumns = [
            'booking_id',
            'member_id',
            'trainer_id',
            'booking_date',
            'timeslot_id',
            'status'
        ];

        public function getBookingsByMonthAndYear($member_id, $trainer_id, $month, $year) {
            $query = "SELECT * FROM $this->table 
                    WHERE member_id = :member_id 
                    AND trainer_id = :trainer_id 
                    AND MONTH(booking_date) = :month 
                    AND YEAR(booking_date) = :year";

            $data = [
                'member_id' => $member_id,
                'trainer_id' => $trainer_id,
                'month' => $month,
                'year' => $year,
            ];

            return $this->query($query, $data);
        }
            
        public function validate($data) {
            $this->errors = [];

            if (empty($data['member_id'])) {
                $this->errors['member_id'] = 'member_id is required';
            } 

            if (empty($data['trainer_id'])) {
                $this->errors['trainer_id'] = 'trainer_id is required';
            } 
        
            if (empty($data['booking_date'])) {
                $this->errors['booking_date'] = 'Date is required';
            } 
    
            if (empty($data['timeslot_id'])) {
                $this->errors['time_slot'] = 'Time slot is required';
            } 
            
            if (!in_array($data['status'], ['pending', 'approved', 'rejected'])) {
                $this->errors['status'] = 'Invalid status value';
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