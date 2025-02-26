<?php

    class M_Workout{

        use Model;

        protected $table = 'workouts';

        // List of allowed columns for insertion or updates
        protected $allowedColumns = [
            'workout_id',
            'workout_name',
            'workout_description',
            'equipment_id',
        ];

    }

?>