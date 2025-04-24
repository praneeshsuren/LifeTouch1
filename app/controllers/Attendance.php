<?php
    class Attendance extends Controller
    {
        public function markAttendance()
        {
            date_default_timezone_set('Asia/Colombo');

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Check if member_id is provided
                if (isset($_POST['member_id'])) {
                    $attendanceModel = new M_Attendance;
                    $member_id = $_POST['member_id'];

                    // Step 1: Retrieve the most recent attendance record
                    $latestRecord = $attendanceModel->getLatestRecord($member_id);

                    if ($latestRecord) {
                        // Step 2: If the record has no time_out, update the time_out
                        if ($latestRecord->time_out === null) {
                            // Update the time_out to the current time for the active session
                            $data = [
                                'time_out' => date('H:i:s') // Current time
                            ];

                            $update = $attendanceModel->update($latestRecord->id, $data, 'id');

                            // Update the attendance data in the database
                            if (!$update) {
                                echo "Time out marked successfully.";
                            } else {
                                echo "Failed to mark time out.";
                            }
                        } else {
                            // If the record already has a time_out, create a new time_in record
                            $data = [
                                'member_id' => $member_id,
                                'date' => date('Y-m-d'),  // Today's date
                                'time_in' => date('H:i:s'), // Current time
                                'time_out' => null  // Time out will be null initially
                            ];

                            // Insert the new attendance data into the database
                            if ($attendanceModel->insert($data)) {
                                echo "New time in marked successfully.";
                            } else {
                                echo "Failed to mark new time in.";
                            }
                        }
                    } else {
                        // Step 3: If no record is found, create a new time_in record
                        $data = [
                            'member_id' => $member_id,
                            'date' => date('Y-m-d'),  // Today's date
                            'time_in' => date('H:i:s'), // Current time
                            'time_out' => null  // Time out will be null initially
                        ];

                        // Insert the attendance data into the database
                        if ($attendanceModel->insert($data)) {
                            echo "Time in marked successfully.";
                        } else {
                            echo "Failed to mark time in.";
                        }
                    }
                } else {
                    echo "Invalid or missing member_id.";
                }
            } else {
                // Handle GET request or show an error
                echo "Invalid request method.";
            }
        }

        public function updateAttendanceGraph(){
            $attendanceModel = new M_Attendance;
            $period = $_GET['period'] ?? 'today'; // Default to 'today'

            $attendanceData = $attendanceModel->getAttendanceDataForGraph($period);

            header('Content-Type: application/json');
            echo json_encode([
                'attendance' => $attendanceData
            ]);
        }

        public function getAttendance()
        {
            // Get parameters from the URL
            $member_id = $_GET['member_id'] ?? null;
            $month = $_GET['month'] ?? null;
            $year = $_GET['year'] ?? null;

            // If any parameters are missing, return an error
            if (!$member_id || !$month || !$year) {
                echo json_encode(['error' => 'Missing required parameters']);
                exit; // Stop further processing
            }

            $attendanceModel = new M_Attendance();
            $attendanceGrouped = $attendanceModel->getAttendanceForMonth($member_id, $month, $year);

            // Ensure the response is in JSON format
            header('Content-Type: application/json');
            echo json_encode(['attendanceByDate' => $attendanceGrouped]);
            exit; // Ensure no additional content is returned
        }

        
    }
?>
