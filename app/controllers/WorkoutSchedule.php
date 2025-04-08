<?php
    class WorkoutSchedule extends Controller
    {
        public function createSchedule()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
                    exit;
                }

                $memberId = $inputData['member_id'];
                $workoutDetails = $inputData['workout_details']; // Array of workout details
                $weightBeginning = $inputData['weight_beginning'];
                $chestMeasurementBeginning = $inputData['chest_measurement_beginning'];
                $bicepMeasurementBeginning = $inputData['bicep_measurement_beginning'];
                $thighMeasurementBeginning = $inputData['thigh_measurement_beginning'];

                // Validate the data
                if (empty($memberId) || empty($workoutDetails)) {
                    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                    exit;
                }

                // Get the number of existing schedules for this member
                $workoutScheduleModel = new M_WorkoutSchedule;
                $existingSchedules = $workoutScheduleModel->findAllSchedulesByMemberId($memberId);

                // Generate the next schedule number (schedule_no)
                $scheduleNo = count($existingSchedules) + 1;

                // Insert schedule into the database
                $success = $workoutScheduleModel->insert([
                    'member_id' => $memberId,
                    'schedule_no' => $scheduleNo,  // Add the schedule_no
                    'workout_details' => json_encode($workoutDetails),
                    'weight_beginning' => $weightBeginning,
                    'chest_measurement_beginning' => $chestMeasurementBeginning,
                    'bicep_measurement_beginning' => $bicepMeasurementBeginning,
                    'thigh_measurement_beginning' => $thighMeasurementBeginning
                ]);

                if ($success) {
                    // After schedule creation, fetch all schedules for the member
                    $schedules = $workoutScheduleModel->findAllSchedulesByMemberId($memberId);

                    if ($schedules !== false) {
                        // Return the schedules data as a JSON response
                        echo json_encode([
                            'success' => true,
                            'message' => 'Workout schedule created successfully.',
                            'schedules' => $schedules
                        ]);
                    } else {
                        echo json_encode(['success' => false, 'message' => 'Error fetching schedules after creation.']);
                    }
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error creating workout schedule.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
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
    }     
?>