<?php

class AutoMarkTimeOut
{
    public function autoMarkTimeOut()
    {
        try {
            date_default_timezone_set('Asia/Colombo');
    
            $currentTime = date('Y-m-d H:i:s');
            $timeLimit = date('Y-m-d H:i:s', strtotime('-1 hours'));
    
            // Create an instance of M_Attendance model
            $attendanceModel = new M_Attendance;
    
            // Retrieve records that need the time_out to be marked
            $records = $attendanceModel->getActiveRecordsExceedingTime($timeLimit);
    
            if ($records) {
                // Loop through the records and mark the time_out
                foreach ($records as $record) {
                    $data = ['time_out' => $currentTime];
    
                    // Update the attendance record in the database
                    if ($attendanceModel->update($record->id, $data, 'id')) {
                        echo "Time out automatically marked for member ID: " . $record->member_id . "\n";
                    } else {
                        echo "Failed to mark time out for member ID: " . $record->member_id . "\n";
                    }
                }
            } else {
                echo "No members with pending time out found.\n";
            }
        } catch (Exception $e) {
            // Log the error or output it
            echo "Error: " . $e->getMessage();
        }
    }
    

}
?>
