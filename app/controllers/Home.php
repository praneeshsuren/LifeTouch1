<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require dirname(__DIR__, 2) . '/vendor/autoload.php';
class Home extends Controller
{
    public function index()
    {
        $eventModel = new M_Event;
        $events = $eventModel->findAll('event_id');

        $data = [
            'events' => $events
        ];

        $this->view('home/home-landingPage', $data);
    }

    public function joinEvent()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $joinModel = new M_JoinEvent();
            $eventModel = new M_Event();

            $data = [
                'event_id'          => $_POST['event_id'],
                'full_name'         => trim($_POST['full_name']),
                'nic'               => trim($_POST['nic']),
                'is_member'         => isset($_POST['is_member']) ? 1 : 0,
                'membership_number' => $_POST['membership_number'] ?? null,
                'email'             => trim($_POST['email']),
            ];

            if ($joinModel->validate($data)) {
                if ($joinModel->insert($data)) {
                    // Fetch event details
                    $eventDetails = $eventModel->getEventById($data['event_id']);

                    if ($eventDetails) {
                        $eventName = $eventDetails->name;
                        $eventDate = date('F j, Y', strtotime($eventDetails->event_date));
                        $startTime = date('g:i A', strtotime($eventDetails->start_time));
                        $eventLocation = $eventDetails->location;


                        $mail = new PHPMailer(true);
                        try {
                            //Server settings
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'amandanethmini100@gmail.com'; // Your Gmail
                            $mail->Password = 'pitf gsqd eibz xejg'; // App password
                            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                            $mail->Port = 587;

                            //Recipients
                            $mail->setFrom('amandanethmini100@gmail.com', 'Life Touch Fitness');
                            $mail->addAddress($data['email'], $data['full_name']);

                            //Content
                            $mail->isHTML(true);
                            $mail->Subject = 'Confirmation: Event Registration';
                            $mail->Body    = "
                            Dear {$data['full_name']},<br><br>
                            Thank you for registering for the event: <strong>{$eventName}</strong>.<br>
                            <ul>
                                <li><strong>Date:</strong> {$eventDate}</li>
                                <li><strong>Time:</strong> {$startTime}</li>
                                <li><strong>Location:</strong> {$eventLocation}</li>
                            </ul>
                            We look forward to seeing you there!<br><br>
                            Best regards,<br>
                            <strong>Life Touch Fitness Team</strong>
                        ";

                            $mail->send();
                            redirect('home?scroll=plans');
                        } catch (Exception $e) {
                            $_SESSION['join_errors'] = ['email' => 'Mailer Error: ' . $mail->ErrorInfo];
                            $_SESSION['form_data'] = $data;
                            redirect('home?scroll=plans');
                        }
                    }
                } else {
                    $_SESSION['join_errors'] = ['database' => 'Failed to save registration'];
                    $_SESSION['form_data'] = $data;
                    redirect('home?scroll=plans');
                }
            } else {
                $_SESSION['join_errors'] = $joinModel->getErrors();
                $_SESSION['form_data'] = $data;
                redirect('home?scroll=plans');
            }
        } else {
            redirect('home?scroll=plans');
        }
    }


    public function checkout()
    {
        $this->view('home/checkout');
    }
}
