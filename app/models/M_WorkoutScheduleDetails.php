<?php

    class M_WorkoutScheduleDetails {
        
        use Model;

        protected $table = 'workout_schedule_details';
        protected $allowedColumns = [
            'schedule_id',
            'member_id',
            'schedule_no',
            'weight_beginning',
            'chest_measurement_beginning',
            'bicep_measurement_beginning',
            'thigh_measurement_beginning',
            'weight_ending',
            'chest_measurement_ending',
            'bicep_measurement_ending',
            'thigh_measurement_ending',
            'created_at',
            'completed_date',
            'created_by'
        ];

        public function findByScheduleId($scheduleId) {
            $data = ['schedule_id' => $scheduleId];
            return $this->first($data);  // Use the `first` method to get the first matching record
        }

        public function findAllSchedulesByMemberId($memberId)
        {
            // Data to filter by member_id
            $data = ['member_id' => $memberId];
            $data_not = [];  // No "not equal" conditions for now
            $order_column = 'created_at';  // Column to order by
        
            // Get schedules for this member
            $schedules = $this->where($data, $data_not, $order_column);
        
            // Return schedules or an empty array if no schedules found
            return $schedules ?: [];  // Returns an empty array if no schedules exist
        }

        public function getLastInsertId()
        {
            // Use the `schedule_id` (auto-increment field) or `created_at` to get the last inserted record
            $query = "SELECT schedule_id FROM $this->table ORDER BY schedule_id DESC LIMIT 1";
            
            // Execute the query to fetch the last inserted schedule_id
            $result = $this->get_row($query);
            
            // Return the retrieved schedule_id
            return $result ? $result->schedule_id : null;
        }

        public function findLastScheduleByMemberId($memberId)
        {
            $query = "SELECT * FROM $this->table WHERE member_id = '$memberId' ORDER BY schedule_no DESC LIMIT 1";

            $result = $this->get_row($query);
            return $result ? $result->schedule_no : 0;  // Return the result or null if not found

        }

        public function findAllCompletedSchedulesByMemberId($memberId)
        {
            $query = "SELECT * FROM $this->table WHERE member_id = :memberID AND completed_date IS NOT NULL ORDER BY created_at DESC";
        
            // Get completed schedules for this member
            $schedules = $this->query($query, ['memberID' => $memberId]);
        
            // Return schedules or an empty array if no schedules found
            return $schedules ?: [];  // Returns an empty array if no schedules exist
        }

        public function findCountByTrainerId($trainerId) {
            // Get the current date and the date 30 days ago
            $date = date('Y-m-d');
            $date_30_days_ago = date('Y-m-d', strtotime('-30 days', strtotime($date)));
        
            // Modify query to count schedules created within the last 30 days
            $query = "SELECT COUNT(*) as count FROM $this->table WHERE created_by = :trainerID AND created_at >= :date_30_days_ago";
        
            // Get the count of schedules for this trainer created in the last 30 days
            $count = $this->query($query, [
                'trainerID' => $trainerId,
                'date_30_days_ago' => $date_30_days_ago
            ]);
        
            // Return the count or 0 if no schedules are found
            return $count ? $count[0]->count : 0;  // Returns 0 if no schedules exist
        }
        

    }

?>