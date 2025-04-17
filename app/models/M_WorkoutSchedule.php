<?php
    class M_WorkoutSchedule {
        use Model;

        protected $table = 'workout_schedule';
        protected $allowedColumns = [
            'row_no',
            'schedule_id',
            'workout_id',
            'description',
            'sets',
            'reps'
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

        public function findByScheduleId($scheduleId) {
            $data = ['schedule_id' => $scheduleId];
            return $this->first($data);  // Use the `first` method to get the first matching record
        }
        
        public function updateSchedule($scheduleId, $workoutDetails, $weightEnd, $chestMeasurementEnd, $bicepMeasurementEnd, $thighMeasurementEnd)
        {
            // Prepare the data to update
            $data = [
                'workout_details' => json_encode($workoutDetails),
                'weight_end' => $weightEnd,
                'chest_measurement_end' => $chestMeasurementEnd,
                'bicep_measurement_end' => $bicepMeasurementEnd,
                'thigh_measurement_end' => $thighMeasurementEnd
            ];

            // Call the update method to update the record
            return $this->update($scheduleId, $data, 'schedule_id');  // Assuming 'schedule_no' is the column used for unique identification
        }

        // Delete workout schedule by schedule_id
        public function deleteSchedule($scheduleId)
        {
            // Call the delete method to delete the record
            return $this->delete($scheduleId, 'schedule_id');  // Assuming 'schedule_no' is the column used for unique identification
        }

        public function findAllByScheduleId($scheduleId)
        {
            $query = "
                SELECT 
                    ws.row_no,
                    ws.schedule_id,
                    ws.workout_id,
                    ws.description,
                    w.workout_name,
                    w.equipment_id,
                    e.name AS equipment_name,
                    ws.sets,
                    ws.reps
                FROM 
                    workout_schedule AS ws
                JOIN 
                    workouts AS w ON ws.workout_id = w.workout_id
                JOIN 
                    equipment AS e ON w.equipment_id = e.equipment_id
                WHERE 
                    ws.schedule_id = :schedule_id
            ";

            return $this->query($query, ['schedule_id' => $scheduleId]);
        }

        }
    
?>
