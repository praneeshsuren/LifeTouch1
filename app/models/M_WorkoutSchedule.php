<?php
    class M_WorkoutSchedule {
        use Model;

        protected $table = 'workout_schedule';
        protected $allowedColumns = [
            'schedule_id',
            'member_id',
            'workout_details',
            'weight_beginning',
            'weight_ending',
            'chest_measurement_beginning',
            'chest_measurement_ending',
            'bicep_measurement_beginning',
            'bicep_measurement_ending',
            'thigh_measurement_beginning',
            'thigh_measurement_ending',
            'schedule_no'
        ];

        public function findByMemberId($memberId) {
            $data = ['member_id' => $memberId];
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
        
    }
    
?>
