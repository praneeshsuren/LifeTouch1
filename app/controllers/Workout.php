<?php

    class Workout extends Controller{

        public function api(){

            $workoutView = new M_WorkoutEquipmentView;
            $workout = $workoutView->findAll();

            header('Content-Type: application/json');
            echo json_encode($workout);
            exit;

        }

        public function createWorkout(){

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get raw POST data
                $data = json_decode(file_get_contents('php://input'), true);
                
                // Check if all required fields are provided
                if (isset($data['name']) && isset($data['description']) && isset($data['equipment_id'])) {

                    $temp = $data;
                    $temp['workout_name'] = $data['name'];
                    $temp['workout_description'] = $data['description'];
                    $temp['equipment_id'] = $data['equipment_id'];
            
        
                    $workoutModel = new M_Workout;
                    $workoutModel->insert($temp);
            
                    // Respond with success
                    echo json_encode(['success' => true]);
                } else {
                    // Respond with failure if data is incomplete
                    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                }
            } else {
                // Respond with failure if not a POST request
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            }

        }

    }

?>