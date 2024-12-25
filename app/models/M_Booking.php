<?php

    //Admin class
    class M_Booking{

        use Model;

        protected $table = 'trainer_booking';
        protected $allowedColumns = [
            'booking_id',
            'booking_date',
            'time_slot',
            'status'
        ];

        public function validate($data) {
            $this->errors = [];
        
            if (empty($data['booking_date'])) {
                $this->errors['booking_date'] = 'Date is required';
            } 
    
            if (empty($data['timeslot'])) {
                $this->errors['timeslot'] = 'Time slot is required';
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
        public function build_calender($month,$year) {
            // Query to fetch bookings for the given month and year
            $query = "SELECT booking_date FROM $this->table WHERE YEAR(booking_date) = ? AND MONTH(booking_date) = ?";
            $bookingsData = $this->query($query, [$year, $month]);


            // Extract booking dates into an array
            $bookings = [];
            if ($bookingsData) {
                foreach ($bookingsData as $row) {
                    $bookings[] = $row->booking_date;
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
            $calendar .="<a class='prevMonth' href='?month=".$prevMonth."&year=".$prevYear."' aria-label='Previous Month'><i class='ph ph-caret-circle-left'></i></a>";
            $calendar .="<div class='monthYear'>$monthName $year</div>";
            $calendar .="<a class='nextMonth' href='?month=".$nextMonth."&year=".$nextYear."' aria-label='Next Month'><i class='ph ph-caret-circle-right'></i></a>";
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

                if(in_array($date, $bookings)){
                    $calendar .="<td class='clickable $today' data-date='$date'>$currentDay</br><a class='booked'>Booked</a></td>";
                } else{
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