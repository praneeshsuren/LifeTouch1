<?php
    class Calendar extends Controller
    {
    
        public function trainerCalendar(){
            $trainer_id = $_GET['id'];
            $month = $_GET['month'] ?? null;
            $year = $_GET['year'] ?? null;

            $bookingModel = new M_Booking();
            $bookings = $bookingModel->getTrainerCalendarMonth($trainer_id, $month, $year);
            header('Content-Type: application/json');
            echo json_encode([
                'bookings' =>$bookings,
            ]);
            exit;
        } 

    }
?>