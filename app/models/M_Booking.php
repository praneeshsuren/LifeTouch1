<?php

    //Admin class
    class M_Booking{

        use Model;

        protected $table = 'booking';
        protected $allowedColumns = [
            'booking_id',
            'member_id',
            'trainer_id',
            'booking_date',
            'timeslot_id',
            'status'
        ];

        public function validate($data) {
            $this->errors = [];

            if (empty($data['member_id'])) {
                $this->errors['member_id'] = 'member_id is required';
            } 

            if (empty($data['trainer_id'])) {
                $this->errors['trainer_id'] = 'trainer_id is required';
            } 
        
            if (empty($data['booking_date'])) {
                $this->errors['booking_date'] = 'Date is required';
            } 
    
            if (empty($data['timeslot_id'])) {
                $this->errors['time_slot'] = 'Time slot is required';
            } 
            
            if (!in_array($data['status'], ['pending', 'approved', 'rejected'])) {
                $this->errors['status'] = 'Invalid status value';
            }
    
            // If there are no errors, return true; otherwise, return false.
            return empty($this->errors);
        }

        // Method to get errors after validation
        public function getErrors()
        {
            return $this->errors;
        }

        //calendar
        public function build_calender($month,$year, $member_id, $trainer_id) {
            // Query to fetch bookings for the given month and year
            $query = "SELECT booking_date, timeslot_id, status 
              FROM $this->table 
              WHERE YEAR(booking_date) = ? 
              AND MONTH(booking_date) = ? 
              AND member_id = ? 
              AND trainer_id = ? ";
            $bookingsData = $this->query($query, [$year, $month, $member_id, $trainer_id]);

            // Extract booking dates into an array
            $bookings = [];
            if ($bookingsData) {
                foreach ($bookingsData as $row) {
                    $bookings[$row->booking_date][$row->timeslot_id] = $row->status;
                }
            }
            $daysOfWeek = array('Sun','Mon','Tues','Wed','Thurs','Fri','Sat');
           
            $firstDayOfMonth = mktime(0,0,0,$month,1,$year);//firstday of month tht is in thee argument of this function
            $dateComponents = getdate($firstDayOfMonth);
            $numberDays = date('t',$firstDayOfMonth); //number of days of month
            $monthName = $dateComponents['month'];//name of month
            $dayIndex = $dateComponents['wday']; //index value 0-6 of 1st day of month

            //current date
            $dateToday = date('Y-m-d');

            // Previous and next month/year
            $prevMonth = $month - 1;
            $nextMonth = $month + 1;
            $prevYear = $year;
            $nextYear = $year;

            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear--;
            }
            if ($nextMonth > 12) {
                $nextMonth = 1;
                $nextYear++;
            }

            //calendar html
            $calendar = "<div class='calendar-header'>";
            $calendar .="<a class='prevMonth' href='?month=".$prevMonth."&year=".$prevYear."&trainer_id=".$trainer_id."' aria-label='Previous Month'><i class='ph ph-caret-circle-left'></i></a>";
            $calendar .="<div class='monthYear'>$monthName $year</div>";
            $calendar .="<a class='nextMonth' href='?month=".$nextMonth."&year=".$nextYear."&trainer_id=".$trainer_id."' aria-label='Next Month'><i class='ph ph-caret-circle-right'></i></a>";
            $calendar .="</div><table class='calendar'>";
            //calendar table
            $calendar .="<tr>";
            foreach($daysOfWeek as $day){
                $calendar .="<th>$day</th>";
            }
            $calendar .="</tr><tr>";
            if($dayIndex > 0){
                for($i = 0; $i < $dayIndex; $i++){
                    $calendar .= "<td class='plain'></td>";
                }
            }
            $currentDay = 1;
            while($currentDay <= $numberDays){
                if($dayIndex == 7){
                    $dayIndex = 0;
                    $calendar .= "</tr><tr>";
                }

                $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
                $monthRel = str_pad($month, 2, "0", STR_PAD_LEFT);
                $date = "$year-$monthRel-$currentDayRel";
                $today = ($date == $dateToday) ? 'today' : '';

                // Check if there are bookings for this date and time slot
                if (isset($bookings[$date])) {
                    foreach ($bookings[$date] as $timeSlot => $status) {
                        if ($status === 'rejected') {
                            $calendar .= "<td class='clickable $today' data-date='$date'>$currentDay</td>";
                        } else {
                        $calendar .= "<td class='clickable $today' data-date='$date' data-timeslot='$timeSlot'>";
                        $calendar .= "$currentDay <br><span class='$status'>" . ucfirst($status) . "</span>";
                        $calendar .= "</td>";
                        }
                    }
                } else {
                    // If no bookings, just display the date
                    $calendar .= "<td class='clickable $today' data-date='$date'>$currentDay</td>";
                }

                $currentDay++;
                $dayIndex++;
            }

            // Pad remaining cells for the last week
            if ($dayIndex != 0) {
                for ($i = $dayIndex; $i < 7; $i++) {
                $calendar .= "<td class='plain'></td>";
                }
            }
            $calendar .= "</tr></table>";
            return $calendar;
        }

    }