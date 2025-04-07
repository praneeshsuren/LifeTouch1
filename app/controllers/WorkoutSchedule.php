<?php
    class WorkoutSchedule extends Controller
    {
        public function createSchedule()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get raw POST data
                $inputData = json_decode(file_get_contents('php://input'), true);

                if (!$inputData) {
                    echo json_encode(['success' => false, 'message' => 'Invalid input data']);
                    exit;
                }

                $memberId = $inputData['member_id'];
                $workoutDetails = $inputData['workout_details']; // Array of workout details

                // Validate the data
                if (empty($memberId) || empty($workoutDetails)) {
                    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                    exit;
                }

                // Create the workout schedule
                $workoutScheduleModel = new M_WorkoutSchedule;

                // Insert schedule into the database
                $success = $workoutScheduleModel->insert([
                    'member_id' => $memberId,
                    'workout_details' => json_encode($workoutDetails)
                ]);

                if ($success) {
                    // After schedule creation, fetch all schedules for the member
                    $schedules = $workoutScheduleModel->findAllSchedulesByMemberId($memberId);

                    // Check if schedules were fetched successfully
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