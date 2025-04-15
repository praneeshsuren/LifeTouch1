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
        ];

    }

?>