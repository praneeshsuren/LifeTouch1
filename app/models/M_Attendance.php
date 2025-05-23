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
    date_default_timezone_set('Asia/Colombo');
    
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
    
        $attendanceData = $this->query($queryByHour, ['today' => $today]);
        
        if ($attendanceData === false) {
            // Handle case where the query failed
            return [
                'attendance_by_hour' => [],  // No data available for today
                'attendance_by_day' => [] // Not used for "today"
            ];
        }
        
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
    }elseif($period === 'yesterday'){

        $yesterday = date('Y-m-d', strtotime('yesterday'));

        $queryByHour = "
            SELECT HOUR(time_in) AS hour, COUNT(*) AS attendance_count 
            FROM $this->table
            WHERE DATE(date) = :yesterday
            AND time_in IS NOT NULL
            GROUP BY HOUR(time_in)
            ORDER BY hour ASC
        ";
    
        $attendanceData = $this->query($queryByHour, ['yesterday' => $yesterday]);
        
        if ($attendanceData === false) {
            // Handle case where the query failed
            return [
                'attendance_by_hour' => [],  // No data available for today
                'attendance_by_day' => [] // Not used for "today"
            ];
        }
        
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
            AND DATE(date) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
            GROUP BY DAYNAME(date)
            ORDER BY FIELD(DAYNAME(date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
        ";

        $rawAttendance = $this->query($queryByDay);

        if ($rawAttendance === false) {
            return [
                'attendance_by_hour' => [],
                'attendance_by_day' => []
            ];
        }

        // Initialize all days to 0
        $attendanceByDay = array_fill_keys(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'], 0);

        // Populate counts from actual query results
        foreach ($rawAttendance as $attendance) {
            $day_name = $attendance->day_name;
            if (isset($attendanceByDay[$day_name])) {
                $attendanceByDay[$day_name] = (int)$attendance->attendance_count;
            }
        }

        // Convert to array of objects like [{ day_name: 'Monday', attendance_count: 15 }, ...]
        $formattedAttendance = [];
        foreach ($attendanceByDay as $day => $count) {
            $formattedAttendance[] = [
                'day_name' => $day,
                'attendance_count' => $count
            ];
        }

        return [
            'attendance_by_hour' => [],
            'attendance_by_day' => $formattedAttendance
        ];

    }
}

public function findByMemberId($member_id)
{
    $query = "SELECT * FROM $this->table WHERE member_id = :member_id";
    $result = $this->query($query, ['member_id' => $member_id]);

    if ($result) {
        return $result;
    } else {
        return null; // No records found for the given member_id
    }
}

public function getAttendanceForMonth($member_id, $month, $year)
{   
    $startOfMonth = date('Y-m-01', strtotime("$year-$month-01"));
    $endOfMonth = date('Y-m-t', strtotime("$year-$month-01"));

    $query = "
        SELECT DATE(date) AS attendance_date, time_in, time_out
        FROM $this->table
        WHERE member_id = :member_id AND DATE(date) BETWEEN :startOfMonth AND :endOfMonth
        ORDER BY date ASC, time_in ASC
    ";

    $params = [
        'member_id' => $member_id,
        'startOfMonth' => $startOfMonth,
        'endOfMonth' => $endOfMonth
    ];

    $attendanceData = $this->query($query, $params);

    // Initialize empty attendanceByDate array
    $attendanceByDate = [];
    
    // If no records found, return an empty object instead of null or an error
    if (!empty($attendanceData)) {
        foreach ($attendanceData as $attendance) {
            $attendanceDate = $attendance->attendance_date;
            if (!isset($attendanceByDate[$attendanceDate])) {
                $attendanceByDate[$attendanceDate] = [];
            }
            $attendanceByDate[$attendanceDate][] = [
                'time_in' => $attendance->time_in,
                'time_out' => $attendance->time_out
            ];
        }
    }

    return $attendanceByDate;
}


}

?>
