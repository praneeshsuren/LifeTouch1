<?php

    //Admin class
    class M_Booking{

        use Model;

        protected $table = 'trainer_booking';
        protected $allowedColumns = [
            'booking_id',
            'booking_date',
            'timeslot',
            'member_id',
            'trainer_id',
            'status',
        ];

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['booking_date'])) {
                $this->errors['booking_date'] = 'Date is required';
            } 
    
            if (empty($data['timeslot'])) {
                $this->errors['timeslot'] = 'Time slot is required';
            } 
        
            if (empty($data['member_id'])) {
                $this->errors['member_id'] = 'member_id is required';
            }
        
            if (empty($data['trainer_id'])) {
                $this->errors['trainer_id'] = 'trainer_id is required';
            }
            
            if (!in_array($data['status'], ['pending', 'approved', 'rejected'])) {
                $this->errors['status'] = 'Invalid status value';
            }
    
            // If there are no errors, return true; otherwise, return false.
            return empty($this->errors);
        }

        //find booking id
        public function findByBookingId($bookingId){
            $data = ['booking_id' => $bookingId];
            return $this->first($data);  // Use the `first` method to get the first matching record
        }

        //find bookings for trainer and date
        public function findByTrainerAndDate($trainerId, $date, $status = 'approved') {
            $data = [
                'trainer_id' => $trainerId,
                'booking_date' => $date,
                'status' => $status
            ];
            return $this->where($data, [], 'booking_date');
        }
      
    }