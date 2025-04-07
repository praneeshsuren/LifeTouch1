<?php
    class M_WorkoutSchedule {
        use Model;

        protected $table = 'workout_schedule';
        protected $allowedColumns = [
            'schedule_id',
            'member_id',
            'workout_details'
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

            return $this->where($data, $data_not, $order_column);
        }
    }
    
?>
