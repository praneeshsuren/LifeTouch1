<?php

    class M_WorkoutScheduleDetails {
        
        use Model;

        protected $table = 'workout_schedule_details';
        protected $allowedColumns = [
            'schedule_id',
            'member_id',
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

    }

?>