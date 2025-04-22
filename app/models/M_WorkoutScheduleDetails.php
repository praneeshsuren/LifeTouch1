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


    }

?>