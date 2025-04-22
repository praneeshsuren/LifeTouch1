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
    public function updateAttendance($id, $data, $idField = 'id')
    {
        $query = "UPDATE $this->table SET time_out = :time_out WHERE $idField = :id";
        return $this->query($query, array_merge(['id' => $id], $data));
    }

    // Fetch attendance data for graph based on period ('today' or 'week')
    public function getAttendanceDataForGraph($period = 'today')
    {
        $today = date('Y-m-d');
        $startOfWeek = date('Y-m-d', strtotime('last sunday midnight'));
        $endOfWeek = date('Y-m-d', strtotime('next saturday midnight'));

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
            $queryByHour = "
                SELECT HOUR(time_in) AS hour, COUNT(*) AS attendance_count 
                FROM $this->table 
                WHERE time_in IS NOT NULL 
                AND DATE(time_in) BETWEEN :startOfWeek AND :endOfWeek
                AND HOUR(time_in) BETWEEN 5 AND 23
                GROUP BY HOUR(time_in) 
                ORDER BY hour ASC
            ";

            $attendanceByHour = $this->query($queryByHour, ['startOfWeek' => $startOfWeek, 'endOfWeek' => $endOfWeek]);
        }

        $queryByDay = "
            SELECT DAYOFWEEK(time_in) AS day, COUNT(*) AS attendance_count 
            FROM $this->table 
            WHERE time_in IS NOT NULL 
            AND DATE(time_in) BETWEEN :startOfWeek AND :endOfWeek
            GROUP BY DAYOFWEEK(time_in) 
            ORDER BY day ASC
        ";

        $attendanceByDay = $this->query($queryByDay, ['startOfWeek' => $startOfWeek, 'endOfWeek' => $endOfWeek]);

        return [
            'attendance_by_hour' => $attendanceByHour,
            'attendance_by_day' => $attendanceByDay
        ];
    }
}

?>
