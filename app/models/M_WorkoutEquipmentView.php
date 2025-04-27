<?php

    class M_WorkoutEquipmentView{

        use Model;

        protected $table = 'workout_with_equipment';

        // List of allowed columns for insertion or updates
        protected $allowedColumns = [
            'workout_id',
            'workout_name',
            'workout_description',
            'equipment_id',
            'equipment_name',
            'image'
        ];

        public function getByWorkoutId($workoutId) {
            // Data to filter by workout_id
            $data = ['workout_id' => $workoutId];
            return $this->first($data);  // Use the `first` method to get the first matching record
        }

    }

?>