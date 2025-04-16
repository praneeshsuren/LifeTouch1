<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class EventParticipant_pdf extends Controller
{
    public function index($event_id)
    {
        // Clear all buffers and ensure no previous output
        while (ob_get_level()) {
            ob_end_clean();
        }

        // Enable error reporting
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            // Verify and correct the autoload path
            $autoloadPath = __DIR__ . '/../models/vendor/autoload.php';
            if (!file_exists($autoloadPath)) {
                throw new Exception("Dompdf not installed. Run: composer require dompdf/dompdf");
            }

            require_once $autoloadPath;
            require_once __DIR__ . '/../models/M_Event.php';
            require_once __DIR__ . '/../models/M_JoinEvent.php';

            // Get event data
            $eventModel = new M_Event();
            $eventArr = $eventModel->getEventById($event_id);
            $event = $eventArr[0] ?? null;



            // Ensure all required properties exist and have default values
            $event->name = $event->name ?? 'Unknown Event';
            $event->event_date = $event->event_date ?? '1970-01-01';
            $event->start_time = $event->start_time ?? '00:00:00';

            if (!$event) {
                header('Content-Type: text/plain');
                die("Event with ID $event_id not found");
            }

            // Get participants
            $participantModel = new M_JoinEvent();
            $participants = $participantModel->where(['event_id' => $event_id], [], 'id');

            if ($participants === false) {
                header('Content-Type: text/plain');
                die("Failed to fetch participants");
            }

            // Generate HTML
            $html = $this->generateHtml($event, $participants);

            // Configure Dompdf
            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'Helvetica'); // Use basic font
            $options->set('tempDir', sys_get_temp_dir()); // Set temp directory

            $dompdf = new Dompdf($options);
            $filename = "Event_Participants_" . preg_replace('/[^a-zA-Z0-9]/', '_', $event->name ?? 'Unknown') . ".pdf";
            $dompdf->loadHtml($html);
            $dompdf->render();

            // Generate filename
            $filename = "Event_Participants_" . preg_replace('/[^a-zA-Z0-9]/', '_', $event->name) . ".pdf";

            // Output PDF
            $dompdf->stream($filename, [
                'Attachment' => 0, // Open in browser
                'Accept-Ranges' => 0 // Disable range requests
            ]);

            exit; // Prevent any further output

        } catch (Exception $e) {
            // Clean any output
            while (ob_get_level()) {
                ob_end_clean();
            }

            // Return plain text error
            header('Content-Type: text/plain');
            die("PDF Generation Failed: " . $e->getMessage());
        }
    }

    private function generateHtml($event, $participants)
    {
        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                body { font-family: Helvetica, sans-serif; padding: 20px; }
                h1 { text-align: center; color: #4A90E2; margin-bottom: 5px; }
                .event-info { text-align: center; margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color:rgb(130, 165, 205); color: white; }
                tr:nth-child(even) { background-color: #f9f9f9; }
            </style>
        </head>
        <body>
            <h1>Event Participant Details</h1>
            <div class="event-info">
                <strong>' . htmlspecialchars($event->name) . '</strong><br>
                Date: ' . date('M d, Y', strtotime($event->event_date)) . ' | 
                Time: ' . date('h:i A', strtotime($event->start_time)) . '
            </div>
            <table>
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Member</th>
                        <th>Membership No</th>
                        <th>NIC</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody>';

        if (!empty($participants)) {
            foreach ($participants as $p) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($p->full_name) . '</td>
                    <td>' . ($p->is_member ? 'Yes' : 'No') . '</td>
                    <td>' . htmlspecialchars($p->membership_number ?? '—') . '</td>
                    <td>' . htmlspecialchars($p->nic) . '</td>
                    <td>' . htmlspecialchars($p->contact_no) . '</td>
                </tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" style="text-align:center;">No participants found</td></tr>';
        }

        $html .= '</tbody></table></body></html>';
        return $html;
    }
}
