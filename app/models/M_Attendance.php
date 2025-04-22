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
        
        // Get the current day of the week (0=Sunday, 1=Monday, ..., 6=Saturday)
        $currentDayOfWeek = date('w', strtotime($today)); // Get the current weekday (0=Sunday, 1=Monday, ..., 6=Saturday)
        
        // Calculate the date range for the last 7 days (from last Tuesday to this Monday)
        $startOfWeek = date('Y-m-d', strtotime("-{$currentDayOfWeek} days")); // Get the previous Sunday
        $startOfWeek = date('Y-m-d', strtotime("last Tuesday", strtotime($startOfWeek))); // Adjust to last Tuesday
    
        $endOfWeek = date('Y-m-d', strtotime("next Monday", strtotime($startOfWeek))); // Get next Monday (this week)
    
        if ($period === 'today') {
            $queryByHour = "
                SELECT HOUR(time_in) AS hour, COUNT(*) AS attendance_count 
                FROM $this->table 
                WHERE time_in IS NOT NULL 
                AND DATE(time_in) = :today
                AND HOUR(time_in) BETWEEN 5 AND 23
                GROUP BY HOUR(time_in) 
                ORDER BY hour ASC
            ";
    
            $attendanceByHour = $this->query($queryByHour, ['today' => $today]);
        } else {
            // Fetch attendance data from last Tuesday to this Monday for the "week" period
            $queryByDay = "
                SELECT DAYNAME(date) AS day_name, COUNT(*) AS attendance_count
                FROM $this->table
                WHERE time_in IS NOT NULL
                AND DATE(date) BETWEEN :startOfWeek AND :endOfWeek
                GROUP BY DAYNAME(date)
                ORDER BY FIELD(DAYNAME(date), 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Monday')
            ";
    
            $attendanceByDay = $this->query($queryByDay, [
                'startOfWeek' => $startOfWeek,
                'endOfWeek' => $endOfWeek
            ]);
        }
    
        return [
            'attendance_by_hour' => $attendanceByHour ?? [],
            'attendance_by_day' => $attendanceByDay ?? []
        ];
    }
    
}

?>
