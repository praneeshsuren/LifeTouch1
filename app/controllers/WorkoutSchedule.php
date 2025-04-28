<?php
class WorkoutSchedule extends Controller
{
    
    public function createSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Load form data
            $member_id = $_POST['member_id'];
            $created_by = $_POST['created_by'];
            $workout_details = $_POST['workout_details'];
            
            // Optional fields (can be empty)
            $weight_beginning = !empty($_POST['weight_beginning']) ? $_POST['weight_beginning'] : null;
            $chest_measurement_beginning = !empty($_POST['chest_measurement_beginning']) ? $_POST['chest_measurement_beginning'] : null;
            $bicep_measurement_beginning = !empty($_POST['bicep_measurement_beginning']) ? $_POST['bicep_measurement_beginning'] : null;
            $thigh_measurement_beginning = !empty($_POST['thigh_measurement_beginning']) ? $_POST['thigh_measurement_beginning'] : null;

            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
            $workoutScheduleModel = new M_WorkoutSchedule;

            try {
                // Get schedule_no for the member
                $schedule_no = $workoutScheduleDetailsModel->findLastScheduleByMemberId($member_id) + 1;

                // Insert workout schedule details
                $scheduleDetailsData = [
                    'schedule_no' => $schedule_no,
                    'created_by' => $created_by,
                    'member_id' => $member_id,
                    'weight_beginning' => $weight_beginning, // Optional field
                    'chest_measurement_beginning' => $chest_measurement_beginning, // Optional field
                    'bicep_measurement_beginning' => $bicep_measurement_beginning, // Optional field
                    'thigh_measurement_beginning' => $thigh_measurement_beginning // Optional field
                ];

                // Insert and get the inserted schedule_id
                $result = $workoutScheduleDetailsModel->insert($scheduleDetailsData);

                if (!$result) {
                    $_SESSION['error'] = 'Failed to insert workout schedule details.';
                    redirect('trainer/members/workoutSchedules?id=' . $member_id);
                    return;
                }

                // Get the schedule_id (auto-incremented)
                $schedule_id = $workoutScheduleDetailsModel->getLastInsertId(); // Get the last inserted ID

                // Insert each workout row
                foreach ($workout_details as $workout) {
                    $row_no = (int) $workout['row_no'];
                    $workout_id = (int) $workout['workout_id'];
                    $description = $workout['description'];
                    $sets = (int) $workout['sets'];
                    $reps = (int) $workout['reps'];

                    $workoutScheduleData = [
                        'row_no' => $row_no,
                        'schedule_id' => $schedule_id, // Use the retrieved schedule_id
                        'workout_id' => $workout_id,
                        'description' => $description,
                        'sets' => $sets,
                        'reps' => $reps
                    ];

                    $insertResult = $workoutScheduleModel->insert($workoutScheduleData);

                    if (!$insertResult) {
                        $_SESSION['error'] = 'Failed to insert workout schedule entry.';
                        redirect('trainer/members/workoutSchedules?id=' . $member_id);
                        return;
                    }
                }

                $_SESSION['success'] = 'Workout schedule created successfully.';

                $message = 'Dear member, a new Workout Schedule has been added to your Profile. Please check your Workout Schedules Page for details.';

                $notificationModel = new M_Notification;
                $notificationModel->createNotification($member_id, $message, 'Member');
                redirect('trainer/members/workoutSchedules?id=' . $member_id);
                return;

            } catch (Exception $e) {
                $_SESSION['error'] = 'An unexpected error occurred: ' . $e->getMessage();
                redirect('trainer/members/workoutSchedules?id=' . $member_id);
                return;
            }
        }
    }

    public function updateWorkoutSchedule()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Load updated weight and measurements
            $schedule_id = $_POST['schedule_id']; // Ensure this is passed from the schedule
            $weight_end = $_POST['weight_end'];
            $chest_measurement_ending = $_POST['chest_measurement_ending'];
            $bicep_measurement_ending = $_POST['bicep_measurement_ending'];
            $thigh_measurement_ending = $_POST['thigh_measurement_ending'];
            $member_id = $_POST['member_id']; // Ensure this is passed from the schedule

            // Optional fields (can be empty)
            $weight_end = !empty($weight_end) ? $weight_end : null;
            $chest_measurement_ending = !empty($chest_measurement_ending) ? $chest_measurement_ending : null;
            $bicep_measurement_ending = !empty($bicep_measurement_ending) ? $bicep_measurement_ending : null;
            $thigh_measurement_ending = !empty($thigh_measurement_ending) ? $thigh_measurement_ending : null;

            // Create the update data array
            $updateData = [
                'weight_ending' => $weight_end,
                'chest_measurement_ending' => $chest_measurement_ending,
                'bicep_measurement_ending' => $bicep_measurement_ending,
                'thigh_measurement_ending' => $thigh_measurement_ending
            ];

            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;

            // Assuming you have a model method to update
            $result = $workoutScheduleDetailsModel->update($schedule_id, $updateData, 'schedule_id');

            if ($result) {
                $_SESSION['success'] = 'Workout schedule updated successfully.';
            } else {
                $_SESSION['error'] = 'Failed to update workout schedule.';
            }

            redirect('trainer/members/workoutSchedules?id=' . $member_id);
        }
    }

    public function markCompleted() {
        // Only process if the method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Read the raw POST data and decode it from JSON
            $inputData = json_decode(file_get_contents('php://input'), true);
    
            if (!$inputData) {
                // Return an error if the JSON is invalid
                echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
                return;
            }
    
            // Extract data from the decoded input
            $schedule_id = $inputData['schedule_id'];
            $completed_date = date('Y-m-d H:i:s'); // Get the current date and time
    
            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
    
            // Update the schedule status with the completed date
            $result = $workoutScheduleDetailsModel->update($schedule_id, ['completed_date' => $completed_date], 'schedule_id');
    
            // Return the appropriate JSON response based on the result of the update
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            // Handle the case where the method is not POST
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }

    public function deleteSchedule() {
        // Only process if the method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Read the raw POST data and decode it from JSON
            $inputData = json_decode(file_get_contents('php://input'), true);
    
            if (!$inputData) {
                // Return an error if the JSON is invalid
                echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
                return;
            }
    
            // Extract data from the decoded input
            $schedule_id = $inputData['schedule_id'];
            $workoutScheduleDetailsModel = new M_WorkoutScheduleDetails;
    
            // Attempt to delete the schedule
            $result = $workoutScheduleDetailsModel->delete($schedule_id, 'schedule_id');
    
            // Return the appropriate JSON response based on the result of the deletion
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        } else {
            // Handle the case where the method is not POST
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    


}
?>
