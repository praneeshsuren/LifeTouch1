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
            $query = "SELECT b.*, ts.slot 
                FROM $this->table AS b
                JOIN time_slots AS ts ON b.timeslot_id = ts.id
                WHERE b.member_id = :member_id 
                AND b.trainer_id = :trainer_id 
                AND MONTH(b.booking_date) = :month 
                AND YEAR(b.booking_date) = :year";

            $data = [
                'member_id' => $member_id,
                'trainer_id' => $trainer_id,
                'month' => $month,
                'year' => $year,
            ];

            return $this->query($query, $data);
        }

        public function bookingsMemberTrainerDetail(){
            $query = "SELECT 
                b.*, 
                m.member_id AS member_id, 
                CONCAT(m.first_name, ' ', m.last_name) AS member_name, 
                t.trainer_id AS trainer_id, 
                CONCAT(t.first_name, ' ', t.last_name) AS trainer_name, 
                ts.slot AS time_slot
              FROM booking AS b
              JOIN time_slots ts ON b.timeslot_id = ts.id
              JOIN member m ON b.member_id = m.member_id
              JOIN trainer t ON b.trainer_id = t.trainer_id";
            
            return $this->query($query);
        }

        public function isBooked($member_id, $trainer_id, $booking_date, $timeslot_id){
            $query = "SELECT * FROM $this->table
                WHERE trainer_id = :trainer_id 
                AND booking_date = :booking_date 
                AND timeslot_id = :timeslot_id";

            $data = [
                'member_id' => $member_id,
                'trainer_id' => $trainer_id, 
                'booking_date' => $booking_date,
                'timeslot_id' => $timeslot_id,
            ];
            $result = $this->query($query, $data);
            if($result){
                return true;
            } else {
                return false;
            }
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