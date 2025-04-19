<?php
use Dompdf\Options;
use Dompdf\Dompdf;

class Workout_pdf extends Controller
{
    public function generate($scheduleId)
    {
        // Clear any previous output
        require_once __DIR__ . '/../models/vendor/autoload.php';

        // Load models
        $scheduleModel = new M_WorkoutSchedule();
        $scheduleDetailsModel = new M_WorkoutScheduleDetails();

        // Get full schedule details (correct source of member_id)
        $schedule = $scheduleDetailsModel->findByScheduleId($scheduleId);

        if (!$schedule) {
            die("Invalid Schedule ID");
        }

        // Get all workouts for this schedule
        $workouts = $scheduleModel->findAllByScheduleId($scheduleId);

        // Generate HTML content
        $html = $this->generatePdfHtml($schedule, $workouts);

        // Configure Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Helvetica');
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        try {
            $dompdf->render();
            
            // Output PDF (preview in browser)
            $dompdf->stream(
                "workout_schedule_{$scheduleId}.pdf", 
                ['Attachment' => 0]
            );
            exit;
        } catch (Exception $e) {
            die("PDF Generation Error: " . $e->getMessage());
        }
    }

    private function generatePdfHtml($schedule, $workouts)
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <title>Workout Details (ID: '.htmlspecialchars($schedule->schedule_id).')</title>
            <style>
                body { font-family: Helvetica, Arial, sans-serif; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h1 { color: #333; margin-bottom: 5px; }
                .info-table { width: 100%; margin-bottom: 20px; }
                .workout-table { width: 100%; border-collapse: collapse; }
                .workout-table th { background-color: #f2f2f2; padding: 8px; text-align: left; }
                .workout-table td { padding: 8px; border-bottom: 1px solid #ddd; }
                .footer { margin-top: 30px; font-size: 12px; text-align: center; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Workout Schedule Report</h1>
                <p>Generated on: '.date('Y-m-d').'</p>
            </div>
            
            <table class="info-table">
                <tr>
                    <td><strong>Member ID:</strong> '.htmlspecialchars($schedule->member_id ?? 'N/A').'</td>
                    <td><strong>Schedule ID:</strong> '.htmlspecialchars($schedule->schedule_id ?? 'N/A').'</td>
                </tr>
            </table>
            
            <table class="workout-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Workout Name</th>
                        <th>Equipment</th>
                        <th>Description</th>
                        <th>Sets</th>
                        <th>Reps</th>
                    </tr>
                </thead>
                <tbody>'
                .$this->generateWorkoutRows($workouts).
                '</tbody>
            </table>
            
            <div class="footer">
                <p>© '.date('Y').' '.APP_NAME.' - All Rights Reserved</p>
            </div>
        </body>
        </html>';
    }

    private function generateWorkoutRows($workouts)
    {
        $html = '';
        foreach ($workouts as $index => $workout) {
            $html .= '
            <tr>
                <td>'.($index+1).'</td>
                <td>'.htmlspecialchars($workout->workout_name).'</td>
                <td>'.htmlspecialchars($workout->equipment_name).'</td>
                <td>'.htmlspecialchars($workout->description).'</td>
                <td>'.htmlspecialchars($workout->sets).'</td>
                <td>'.htmlspecialchars($workout->reps).'</td>
            </tr>';
        }
        return $html;
    }
}
