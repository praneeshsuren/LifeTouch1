<?php

    class Workout extends Controller{

        public function api(){

            $workoutView = new M_WorkoutEquipmentView;
            $workout = $workoutView->findAll();

            header('Content-Type: application/json');
            echo json_encode($workout);
            exit;

        }

        public function createWorkout() {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get raw POST data
                $data = json_decode(file_get_contents('php://input'), true);
        
                // Validate required fields
                if (empty($data['name'])) {
                    echo json_encode(['success' => false, 'message' => 'Workout name is required.']);
                    return;
                }
        
                if (empty($data['description'])) {
                    echo json_encode(['success' => false, 'message' => 'Description is required.']);
                    return;
                }
        
                if (empty($data['equipment_id'])) {
                    echo json_encode(['success' => false, 'message' => 'Please select equipment.']);
                    return;
                }
        
                // Optionally, add further validation (e.g., name length, equipment_id validation)
        
                // Proceed with inserting the workout into the database
                $temp = $data;
                $temp['workout_name'] = $data['name'];
                $temp['workout_description'] = $data['description'];
                $temp['equipment_id'] = $data['equipment_id'];
        
                $workoutModel = new M_Workout;
                $workoutModel->insert($temp);
        
                // Respond with success
                echo json_encode(['success' => true]);
            } else {
                // Respond with failure if not a POST request
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            }
        }
        
        public function getEquipmentSuggestions() {
        
                try {
                    if (isset($_GET['query'])) {
                        $query = $_GET['query'];
                        $equipmentModel = new M_Equipment;
                        $suggestions = $equipmentModel->getSuggestionsByName($query);
        
                    // Check if suggestions are found
                        if ($suggestions) {
                            echo json_encode($suggestions);
                        } else {
                            echo json_encode([]);
                        }
                    } else {
                        throw new Exception("Query parameter is missing");
                    }
                } catch (Exception $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }
        }

        

    }

?>