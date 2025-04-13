<?php
class WorkoutSchedule extends Controller
{
    
    public function createSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the posted data from the form submission
            $member_id = $_POST['member_id'];
            $created_by = $_POST['created_by'];
            $workout_details = $_POST['workout_details']; // This will be an array
            $weight_beginning = $_POST['weight_beginning'];
            $chest_measurement_beginning = $_POST['chest_measurement_beginning'];
            $bicep_measurement_beginning = $_POST['bicep_measurement_beginning'];
            $thigh_measurement_beginning = $_POST['thigh_measurement_beginning'];
    
            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
            
            $schedule_id = $workoutScheduleDetailsModel->countAll() + 1;
    
            // Insert the workout schedule details
            $scheduleDetailsData = [
                'schedule_id' => $schedule_id,
                'created_by' => $created_by,
                'member_id' => $member_id,
                'weight_beginning' => $weight_beginning,
                'chest_measurement_beginning' => $chest_measurement_beginning,
                'bicep_measurement_beginning' => $bicep_measurement_beginning,
                'thigh_measurement_beginning' => $thigh_measurement_beginning
            ];
    
            $result = $workoutScheduleDetailsModel->insert($scheduleDetailsData);
    
            if (!$result) {
                echo 'Failed to insert workout schedule details.';
                return;
            }
    
            // Insert each workout schedule entry into the workout_schedule table
            $workoutScheduleModel = new M_WorkoutSchedule;

    
            foreach ($workout_details as $workout) {
                $workout_id = (int) $workout['workout_id'];  // Cast to integer
                $sets = (int) $workout['sets'];  // Cast to integer
                $reps = (int) $workout['reps'];  // Cast to integer
    
                // Prepare data for workout_schedule table
                $workoutScheduleData = [
                    'schedule_id' => $schedule_id,
                    'workout_id' => $workout_id,
                    'sets' => $sets,
                    'reps' => $reps
                ];
    
                $insertResult = $workoutScheduleModel->insert($workoutScheduleData);
    
                if (!$insertResult) {
                    echo 'Failed to insert workout schedule entry.';
                    return;
                }
            }
    
            // Return success response
            echo 'Workout schedule created successfully.';
        }
    }
    


    public function getMemberWorkouts()
    {
        // Get the memberId from the query string (GET parameter)
        if (isset($_GET['id'])) {
            $memberId = $_GET['id'];
        } else {
            echo json_encode(['error' => 'Member ID is missing']);
            exit;
        }

        // Fetch the workouts for the specified member
        $workoutScheduleModel = new M_WorkoutSchedule;
        $schedules = $workoutScheduleModel->findAllSchedulesByMemberId($memberId);

        // Check if any schedules were found
        if ($schedules) {
            // Return schedules as JSON
            echo json_encode([
                'success' => true,
                'schedules' => $schedules
            ]);
        } else {
            // Return an error message if no schedules are found
            echo json_encode([
                'success' => false,
                'message' => 'No schedules found for this member.'
            ]);
        }
        exit;
    }

    public function getWorkoutSchedule() {
        if (!isset($_GET['id'])) {
            echo json_encode(['success' => false, 'message' => 'Schedule ID is missing']);
            exit;
        }
    
        $scheduleId = $_GET['id'];
        
        // Fetch the workout schedule by ID
        $workoutScheduleModel = new M_WorkoutSchedule;
        $schedule = $workoutScheduleModel->findByScheduleId($scheduleId);
    
        if ($schedule) {
            // Check if workout_details is set and valid
                // Decode the workout_details JSON string
                $workoutDetails = json_decode($schedule->workout_details, true);

                var_dump($schedule);
                // Check for JSON decode errors
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo json_encode(['success' => false, 'message' => 'Failed to decode workout details']);
                    exit;
                }
    
                // Instantiate model for workout_with_equipment
                $workoutWithEquipmentModel = new M_WorkoutEquipmentView;
    
                foreach ($workoutDetails as &$detail) {
                    // Fetch workout info based on workout_id
                    $workoutInfo = $workoutWithEquipmentModel->getByWorkoutId($detail['workout_id']);
    
                    if ($workoutInfo) {
                        $detail['workout_name'] = $workoutInfo['workout_name'];
                        $detail['equipment_id'] = $workoutInfo['equipment_id'];
                        $detail['equipment_name'] = $workoutInfo['equipment_name'];
                    } else {
                        // Fallback if no data found
                        $detail['workout_name'] = 'N/A';
                        $detail['equipment_id'] = 'N/A';
                        $detail['equipment_name'] = 'N/A';
                    }
                }
    
                // Replace and send the response
                $schedule['workout_details'] = $workoutDetails;
    
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'schedule' => $schedule
                ]);

        } else {
            echo json_encode(['success' => false, 'message' => 'Schedule not found']);
        }
        exit;
    }


    // Update a workout schedule by schedule_id
    public function updateSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
                echo json_encode(['success' => false, 'message' => 'Invalid input data']);
                exit;
            }

            $scheduleId = $inputData['schedule_id'];
            $workoutDetails = $inputData['workout_details']; // Array of workout details

            // Optional fields
            $weightEnd = isset($inputData['weight_end']) ? $inputData['weight_end'] : null;
            $chestMeasurementEnd = isset($inputData['chest_measurement_end']) ? $inputData['chest_measurement_end'] : null;
            $bicepMeasurementEnd = isset($inputData['bicep_measurement_end']) ? $inputData['bicep_measurement_end'] : null;
            $thighMeasurementEnd = isset($inputData['thigh_measurement_end']) ? $inputData['thigh_measurement_end'] : null;

            // Validate the required fields
            if (empty($scheduleId) || empty($workoutDetails)) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit;
            }

            // Update the schedule in the database
            $workoutScheduleModel = new M_WorkoutSchedule;
            $success = $workoutScheduleModel->updateSchedule($scheduleId, $workoutDetails, $weightEnd, $chestMeasurementEnd, $bicepMeasurementEnd, $thighMeasurementEnd);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Workout schedule updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating workout schedule']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    // Delete a workout schedule by schedule_id
    public function deleteSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inputData = json_decode(file_get_contents('php://input'), true);

            if (!$inputData) {
                echo json_encode(['success' => false, 'message' => 'Invalid input data']);
                exit;
            }

            $scheduleId = $inputData['schedule_id'];

            if (empty($scheduleId)) {
                echo json_encode(['success' => false, 'message' => 'Missing schedule ID']);
                exit;
            }

            // Delete the schedule from the database
            $workoutScheduleModel = new M_WorkoutSchedule;
            $success = $workoutScheduleModel->deleteSchedule($scheduleId);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Workout schedule deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error deleting workout schedule']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function markCompleted(){

    }
}
?>
