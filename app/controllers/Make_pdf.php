<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/../models/M_Equipment.php';
require_once __DIR__ . '/../models/M_service.php';

class Make_pdf extends Controller
{
    public function index()
    {
        // Load Dompdf library
        require __DIR__ . '/../models/vendor/autoload.php';

        // Create an instance of the M_Equipment class
        $equipmentModel = new M_Equipment();
        $serviceModel = new M_Service();

        // Get overdue equipment service data
        $overdueServices = $serviceModel->getOverdueServices();

        // Debugging: Check if data is being fetched correctly
        if (empty($overdueServices)) {
            $html = "<h1 style='color:red; text-align:center;'>No Overdue Equipment Found</h1>";
            $this->generatePdf($html);
            return;
        }

        // Generate the HTML content for the PDF
        $html = $this->generateHtmlReport($overdueServices);

        // Increase memory and execution time limits
        ini_set('memory_limit', '512M');
        set_time_limit(120);

        // Generate and stream the PDF
        $this->generatePdf($html);
    }

    private function generateHtmlReport($overdueServices)
    {
        $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Overdue Equipment Report</title>
            <style>
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f9f9f9;
                    color: #333;
                    margin: 0;
                    padding: 20px;
                }
                .report-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    color: #d9534f;
                    text-align: center;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #d9534f;
                    color: white;
                }
                tr:nth-child(even) {
                    background-color: #f2dede;
                }
            </style>
        </head>
        <body>
            <div class='report-container'>
                <h1>Overdue Equipment Service Report</h1>
                <table>
                    <thead>
                        <tr>
                            <th>Equipment Name</th>
                            <th>Last Service Date</th>
                            <th>Next Service Date</th>
                            <th>Service Cost</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($overdueServices as $service) {
            $html .= "<tr>
                        <td>{$service->equipment_name}</td>
                        <td>{$service->service_date}</td>
                        <td style='color: red;'>{$service->next_service_date}</td>
                        <td>Rs {$service->service_cost}</td>
                      </tr>";
        }

        $html .= "
                    </tbody>
                </table>
            </div>
        </body>
        </html>";

        return $html;
    }

    private function generatePdf($html)
    {
        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
        $dompdf->setPaper("A4", "portrait");
        $dompdf->loadHtml($html);
        $dompdf->render();

        // Debugging: Save the PDF to check if it generates properly
        file_put_contents("debug.pdf", $dompdf->output());

        // Stream the PDF to the browser
        $dompdf->stream('overdue_equipment_report.pdf', ['Attachment' => 0]);
    }
}
?>
