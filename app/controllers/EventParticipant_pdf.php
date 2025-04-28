<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class EventParticipant_pdf extends Controller
{
    public function index($event_id)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            $autoloadPath = __DIR__ . '/../models/vendor/autoload.php';
            if (!file_exists($autoloadPath)) {
                throw new Exception("Dompdf not installed. Run: composer require dompdf/dompdf");
            }

            require_once $autoloadPath;
            require_once __DIR__ . '/../models/M_Event.php';
            require_once __DIR__ . '/../models/M_JoinEvent.php';

            $eventModel = new M_Event();
            $eventArr = $eventModel->getEventByIdd($event_id);
            $event = $eventArr[0] ?? null;



            $event->name = $event->name ?? 'Unknown Event';
            $event->event_date = $event->event_date ?? '1970-01-01';
            $event->start_time = $event->start_time ?? '00:00:00';

            if (!$event) {
                header('Content-Type: text/plain');
                die("Event with ID $event_id not found");
            }

            $participantModel = new M_JoinEvent();
            $participants = $participantModel->where(['event_id' => $event_id], [], 'id');

            if ($participants === false) {
                header('Content-Type: text/plain');
                die("Failed to fetch participants");
            }

            $html = $this->generateHtml($event, $participants);

            $options = new Options();
            $options->set('isRemoteEnabled', true);
            $options->set('isHtml5ParserEnabled', true);
            $options->set('defaultFont', 'Helvetica'); 
            $options->set('tempDir', sys_get_temp_dir()); 

            $dompdf = new Dompdf($options);
            $filename = "Event_Participants_" . preg_replace('/[^a-zA-Z0-9]/', '_', $event->name ?? 'Unknown') . ".pdf";
            $dompdf->loadHtml($html);
            $dompdf->render();

            $filename = "Event_Participants_" . preg_replace('/[^a-zA-Z0-9]/', '_', $event->name) . ".pdf";

            $dompdf->stream($filename, [
                'Attachment' => 0, 
                'Accept-Ranges' => 0 
            ]);

            exit; 

        } catch (Exception $e) {
            while (ob_get_level()) {
                ob_end_clean();
            }

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
.footer { margin-top: 30px; font-size: 12px; text-align: center; }
}
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
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                ';

        if (!empty($participants)) {
            foreach ($participants as $p) {
                $html .= '<tr>
                    <td>' . htmlspecialchars($p->full_name) . '</td>
                    <td>' . ($p->is_member ? 'Yes' : 'No') . '</td>
                    <td>' . htmlspecialchars($p->membership_number ?? '—') . '</td>
                    <td>' . htmlspecialchars($p->nic) . '</td>
                    <td>' . htmlspecialchars($p->email) . '</td>
                </tr>
            ';
            }
        } else {
            $html .= '<tr><td colspan="5" style="text-align:center;">No participants found</td></tr>';
        }

        $html .= '</tbody></table></body></html>';
        return $html;
    }
}
