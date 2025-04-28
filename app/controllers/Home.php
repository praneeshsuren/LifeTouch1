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


                        $mail = new PHPMailer(true);//throw an new exception if something goes wrong
                        try {
                            //Server settings
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com'; //specify smtp server
                            $mail->SMTPAuth = true; //ensure that the sender has permission to send emails
                            $mail->Username = 'amandanethmini100@gmail.com'; // Your Gmail
                            $mail->Password = 'niib zlpx xskb bmag'; // App password
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


public function checkout(){
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['payment_data'] = [
            'event_id' => $_POST['event_id'],
            'full_name' => $_POST['full_name'],
            'nic' => $_POST['nic'],
            'contact_no' => $_POST['contact_no'],
            'member_id' => !empty($_POST['membership_number']) ? $_POST['membership_number'] : null
        ];

        $this->view('home/checkout',[
            'session' => $_SESSION['payment_data'],
        ]);
    } else {
        redirect('home?scroll=plans');
    }
}
public function cardPayment($action = null){
    $event_Model = new M_Event();
    $event = $event_Model->findAll();
    if($action === 'api'){
        header('Content-type: application/json');
        $payment_data = isset($_SESSION['payment_data']) ? $_SESSION['payment_data'] : null;
        echo json_encode([
            'event' => $event,
            'session' => $payment_data
        ]);
        exit;
    }
}

public function createPayment(){
    \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));

     // Read the incoming JSON from the frontend
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    $amount = isset($jsonObj->amount) ? intval($jsonObj->amount) * 100 : 300000;
    $currency = 'lkr';

    try {
        // Create a Payment Intent using Stripe's API
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_types' => ['card'],
        ]);

        // Respond with the client secret for the frontend to handle
        echo json_encode([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        error_log("Stripe Error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} 

public function Payment($action = null) {
    $eventPayment_Model = new M_EventPayment();
    $payment = $eventPayment_Model->findAll();
    $event_Model = new M_Event();
    $event = $event_Model->findAll();

    if ($action === 'api') {
        header('Content-Type: application/json');
        echo json_encode([
            'payment' => $payment,
            'event' => $event
        ]);
        exit;
    } else if ($action === 'savePayment'){
        if($_SERVER['REQUEST_METHOD'] === "POST") {
            header('Content-Type: application/json');

            $event_id = $_POST['event_id'] ?? null;
            $name = $_POST['name'] ?? null;
            $payment_intent_id = $_POST['payment_intent_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $number = $_POST['contact_no'] ?? null;
            $nic = $_POST['nic'] ?? null;
            $member_id =  !empty($_POST['member_id']) ? $_POST['member_id'] : null;

            if (!$event_id || !$name || !$payment_intent_id || !$status || !$nic || !$number) {
                echo json_encode([
                    "success" => false,
                    "message" => "Missing required fields"
                ]);
                exit;
            }

            $data = [
                'event_id' =>$event_id,
                'name' => $name,
                'payment_intent_id' => $payment_intent_id,
                'status' => $status,
                'nic' => $nic,
                'number' => $number,
                'member_id' => $member_id
            ];
            $result = $eventPayment_Model->insert($data);
            echo json_encode([
                "success" => $result ? true : false, 
                "message" => $result ? "Payment successful and saved!" : "Payment succeeded, but failed to save payment info"
            ]);
            exit;
            
        }
        else {
            // This line will prevent HTML response
            header('Content-Type: application/json');
            echo json_encode(["success" => false, "message" => "Invalid request method"]);
            exit;
        }
       
    }
    redirect('home?scroll=plans');
}

public function contact($action = null) {
    $contact_Model = new M_Contact();
    $contact = $contact_Model->findAll();

    if ($action === 'api') {
        header('Content-Type: application/json');
        echo json_encode([
            'contact' => $contact
        ]);
        exit;
    } elseif ($action === 'add') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            $name = $_POST['name'] ?? null;
            $email = $_POST['email'] ?? null;
            $msg = $_POST['message'] ?? null;

            // Check if all fields are filled out
            if (!$name || !$email || !$msg) {
                echo json_encode([
                    "success" => false,
                    "message" => "All fields are required."
                ]);
                exit;
            }

            // Save the contact message to the database
            $data = [
                'name' => $name,
                'email' => $email,
                'msg' => $msg
            ];  

            $result = $contact_Model->insert($data);

            // If the message was saved successfully, send the email
            if ($result) {
                // Send thank you email to the user
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = 'amandanethmini100@gmail.com'; // Your Gmail address
                    $mail->Password = 'niib zlpx xskb bmag'; // Your Gmail app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('amandanethmini100@gmail.com', 'Life Touch Fitness');
                    $mail->addAddress($email, $name);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Thank you for contacting us!';
                    $mail->Body    = "
                    Dear {$name},<br><br>
                    Thank you for reaching out to Life Touch Fitness! We have received your message and will get back to you as soon as possible.<br><br>
                    <strong>Your Message:</strong><br>
                    {$msg}<br><br>
                    Best regards,<br>
                    <strong>Life Touch Fitness Team</strong>
                ";

                    // Send the email
                    $mail->send();

                    echo json_encode([
                        "success" => true,
                        "message" => "Inquiry saved and thank you email sent!"
                    ]);
                    exit;

                } catch (Exception $e) {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to send thank you email: " . $mail->ErrorInfo
                    ]);
                    exit;
                }
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to save inquiry"
                ]);
                exit;
            }
        }
    }
}

}
