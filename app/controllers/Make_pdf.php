<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require_once __DIR__ . '/User.php';
require_once __DIR__ . '/../models/M_Member.php'; // Include your M_Member class

class Make_pdf extends Controller
{
    public function index()
    {
        // Load Dompdf library
        require __DIR__ . '/../models/vendor/autoload.php';

        // Retrieve the full path from the URL
        $uri = $_SERVER['REQUEST_URI'];  // For example: /make_pdf/MB/F/6020

        // Remove the base part of the URL to isolate the member ID path
        $pathParts = explode('/make_pdf/', $uri);
        if (isset($pathParts[1])) {
            // The member ID will be after '/make_pdf/'
            $memberId = $pathParts[1];
        } else {
            $memberId = null;
        }

        // Handle missing or invalid member ID
        if (!$memberId) {
            $html = "<h1 style='color:red; text-align:center;'>No Member ID provided</h1>";
            $this->generatePdf($html);
            return;
        }

        // Create an instance of the M_Member class
        $memberModel = new M_Member();

        // Get the member data using the dynamic ID
        $memberData = $memberModel->findByMemberId($memberId);

        // Handle member not found
        if (!$memberData) {
            $html = "<h1 style='color:red; text-align:center;'>Member not found</h1>";
            $this->generatePdf($html);
            return;
        }

        // Generate the HTML content for the PDF
        $html = $this->generateHtmlReport($memberData);

        // Generate and stream the PDF
        $this->generatePdf($html);
    }

    /**
     * Generate the HTML content for the PDF report.
     *
     * @param object $memberData The member data object.
     * @return string The HTML content.
     */
    private function generateHtmlReport($memberData)
    {
        $createdDate = !empty($memberData->membership_plan_created_date) ? $memberData->membership_plan_created_date : $memberData->created_at;
        $startDate = (new DateTime($createdDate))->format('Y-m-d');
        $endDate = new DateTime($createdDate);

        // Calculate the end date based on the membership plan
        switch ($memberData->membership_plan) {
            case 'Monthly':
                $endDate->modify('+1 month');
                break;
            case 'Quarterly':
                $endDate->modify('+4 months');
                break;
            case 'Semi-Annually':
                $endDate->modify('+6 months');
                break;
            case 'Annually':
                $endDate->modify('+1 year');
                break;
        }
        $endDateFormatted = $endDate->format('Y-m-d');

        // HTML content with advanced styling
        $html = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Membership Plan Report</title>
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
                    color: #4CAF50;
                    text-align: center;
                    margin-bottom: 20px;
                }
                h2 {
                    color: #2196F3;
                    text-align: center;
                    margin-bottom: 20px;
                }
                .details {
                    margin-bottom: 20px;
                }
                .details p {
                    font-size: 16px;
                    line-height: 1.6;
                    margin: 10px 0;
                }
                .details p strong {
                    color: #555;
                }
                .dates {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 20px;
                }
                .dates p {
                    font-size: 14px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class='report-container'>
                <h1>Membership Plan Report</h1>
                <h2>Hi {$memberData->first_name} {$memberData->last_name}!</h2>
                <div class='details'>
                    <p><strong>Membership Plan:</strong> {$memberData->membership_plan}</p>
                    <div class='dates'>
                        <p style='color: green;'><strong>Start Date:</strong> {$startDate}</p>
                        <p style='color: red;'><strong>End Date:</strong> {$endDateFormatted}</p>
                    </div>
                </div>
            </div>
        </body>
        </html>";

        return $html;
    }

    /**
     * Generate and stream the PDF.
     *
     * @param string $html The HTML content to convert to PDF.
     */
    private function generatePdf($html)
    {
        // Set up Dompdf options
        $options = new Options();
        $options->setChroot(__DIR__);
        $options->setIsRemoteEnabled(true); // Enable remote files (e.g., images, CSS)

        // Initialize Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->setPaper("A4", "portrait");

        // Load HTML content
        $dompdf->loadHtml($html);

        // Render the PDF
        $dompdf->render();

        // Add metadata
        $dompdf->addInfo("Title", "Member Details");

        // Stream the PDF to the browser
        $dompdf->stream('member.pdf', ['Attachment' => 0]);
    }
}