<?php

class M_Attendance
{
    use Model;

    protected $table = 'attendance';
    protected $allowedColumns = [
        'id',
        'member_id',
        'date',
        'time_in',
        'time_out'
    ];

    // Fetch the latest attendance record for a specific member
    public function getLatestRecord($member_id)
    {
        $query = "SELECT * FROM $this->table WHERE member_id = :member_id ORDER BY date DESC, time_in DESC LIMIT 1";
        $params = ['member_id' => $member_id];

        $record = $this->get_row($query, $params);

        return $record ? $record : null;
    }

    // Fetch records where time_out is NULL and time_in is older than a specific time limit
    public function getActiveRecordsExceedingTime($timeLimit)
    {
        $query = "SELECT * FROM $this->table WHERE time_out IS NULL AND time_in < :timeLimit";
        return $this->query($query, ['timeLimit' => $timeLimit]);
    }

    // Update attendance record by setting time_out
    public function getAttendanceDataForGraph($period = 'today')
{
    $today = date('Y-m-d');
    $currentHour = date('H');  // Get the current hour for handling NULL time_out

    // Get the current day of the week (0=Sunday, 1=Monday, ..., 6=Saturday)
    $currentDayOfWeek = date('w', strtotime($today)); // Get the current weekday (0=Sunday, 1=Monday, ..., 6=Saturday)
    
    // Calculate the date range for the last 7 days (from last Tuesday to this Monday)
    $startOfWeek = date('Y-m-d', strtotime("-{$currentDayOfWeek} days")); // Get the previous Sunday
    $startOfWeek = date('Y-m-d', strtotime("last Tuesday", strtotime($startOfWeek))); // Adjust to last Tuesday
    $endOfWeek = date('Y-m-d', strtotime("next Monday", strtotime($startOfWeek))); // Get next Monday (this week)
    
    if ($period === 'today') {
        // Query to get attendance for today
        $queryByHour = "
            SELECT HOUR(time_in) AS hour, COUNT(*) AS attendance_count 
            FROM $this->table
            WHERE DATE(date) = :today
            AND time_in IS NOT NULL
            GROUP BY HOUR(time_in)
            ORDER BY hour ASC
        ";
    
        // Get the attendance data for today
        $attendanceData = $this->query($queryByHour, ['today' => $today]);
        
        $attendanceByHour = array_fill(5, 19, 0); // Initialize array from 5 AM to 11 PM

    // Populate the attendance count for each hour
    foreach ($attendanceData as $attendance) {
        $hour = (int)$attendance->hour;  // Access as an object
        $attendanceByHour[$hour] = (int)$attendance->attendance_count;
    }

        return [
            'attendance_by_hour' => $attendanceByHour,  // Hourly attendance for the day
            'attendance_by_day' => [] // Not used for "today"
        ];
    } else {
        // Fetch attendance data from last Tuesday to this Monday for the "week" period
        $queryByDay = "
            SELECT DAYNAME(date) AS day_name, COUNT(*) AS attendance_count
            FROM $this->table
            WHERE time_in IS NOT NULL
            AND DATE(date) BETWEEN :startOfWeek AND :endOfWeek
            GROUP BY DAYNAME(date)
            ORDER BY FIELD(DAYNAME(date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
        ";
    
        $attendanceByDay = $this->query($queryByDay, [
            'startOfWeek' => $startOfWeek,
            'endOfWeek' => $endOfWeek
        ]);

        return [
            'attendance_by_hour' => [],  // No hourly data for "week" period
            'attendance_by_day' => $attendanceByDay ?? []  // Return attendance by day for the week
        ];
    }
}

    
}

?>
