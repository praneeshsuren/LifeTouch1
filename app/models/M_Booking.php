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
                JOIN timeslot AS ts ON b.timeslot_id = ts.id
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

        public function bookingsForTrainer($trainer_id){
            $query = "SELECT 
                b.*, 
                m.member_id AS member_id, 
                CONCAT(m.first_name, ' ', m.last_name) AS member_name, 
                ts.slot AS timeslot
                FROM booking AS b
                JOIN timeslot ts ON b.timeslot_id = ts.id
                JOIN member m ON b.member_id = m.member_id
                WHERE b.trainer_id = :trainer_id 
            ";

            return $this->query($query, ['trainer_id' => $trainer_id]);
        }

        public function getTrainerCalendarMonth($trainer_id, $month, $year){
            $startOfMonth = date('Y-m-01', strtotime("$year-$month-01"));
            $endOfMonth = date('Y-m-t', strtotime("$year-$month-01"));
   
            $query = "
                SELECT 
                b.*, 
                m.member_id AS member_id, 
                CONCAT(m.first_name, ' ', m.last_name) AS member_name, 
                ts.slot AS timeslot
                FROM booking AS b
                JOIN timeslot ts ON b.timeslot_id = ts.id
                JOIN member m ON b.member_id = m.member_id
                WHERE b.trainer_id = :trainer_id AND DATE(b.booking_date) BETWEEN :startOfMonth AND :endOfMonth
                AND b.status = 'booked'
                ORDER BY b.booking_date ASC
            ";

            $params = [
                'trainer_id' => $trainer_id,
                'startOfMonth' => $startOfMonth,
                'endOfMonth' => $endOfMonth
            ];

            $bookingData = $this->query($query, $params);

            $bookingByDate = [];
            
            if (!empty($bookingData)) {
                foreach ($bookingData as $booking) {
                    $bookingDate = $booking->booking_date;
                    if (!isset($bookingByDate[$bookingDate])) {
                        $bookingByDate[$bookingDate] = [];
                    }
                    $bookingByDate[$bookingDate][] = [
                        'member_name' => $booking->member_name,
                        'timeslot' => $booking->timeslot
                    ];
                }
            }

            return $bookingByDate;
        }

        public function bookingsForMember($member_id){
            $query = "SELECT 
                b.*,  
                t.trainer_id AS trainer_id, 
                CONCAT(t.first_name, ' ', t.last_name) AS trainer_name, 
                ts.slot AS timeslot
              FROM booking AS b
              JOIN timeslot ts ON b.timeslot_id = ts.id
              JOIN member m ON b.member_id = m.member_id
              JOIN trainer t ON b.trainer_id = t.trainer_id
              WHERE b.member_id = :member_id";
            
            return $this->query($query, ['member_id' => $member_id]);
        }

        public function bookingsForAdmin(){
            $query = "SELECT 
                b.*, 
                m.member_id AS member_id, 
                CONCAT(m.first_name, ' ', m.last_name) AS member_name, 
                t.trainer_id AS trainer_id, 
                CONCAT(t.first_name, ' ', t.last_name) AS trainer_name, 
                ts.slot AS timeslot
              FROM booking AS b
              JOIN timeslot ts ON b.timeslot_id = ts.id
              JOIN member m ON b.member_id = m.member_id
              JOIN trainer t ON b.trainer_id = t.trainer_id";
            
            return $this->query($query);
        }

        public function isBooked($trainer_id){
            $query = "SELECT booking_date, timeslot_id, status FROM $this->table
              WHERE trainer_id = :trainer_id";

            return $this->query($query, ['trainer_id' => $trainer_id]);
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
    
            return empty($this->errors);
        }

        public function getErrors()
        {
            return $this->errors;
        }

        public function findCountByTrainerId($trainer_id) {
            $date = date('Y-m-d');
            $date_30_days_ago = date('Y-m-d', strtotime('-30 days', strtotime($date)));
        
            $query = "SELECT COUNT(*) AS count 
                      FROM $this->table 
                      WHERE trainer_id = :trainer_id 
                      AND created_at >= :date_30_days_ago 
                      AND status = 'booked'";
        
            $data = [
                'trainer_id' => $trainer_id,
                'date_30_days_ago' => $date_30_days_ago
            ];
        
            $result = $this->query($query, $data);
            return $result ? $result[0]->count : 0; 
        }
        
    
    }