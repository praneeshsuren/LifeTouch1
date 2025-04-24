<?php

use GrahamCampbell\ResultType\Success;

class Report extends Controller
{
    use Database;
    public function __construct()
    {
        // Check if the user is logged in as a manager
        $this->checkAuth('manager');
    }
    public function index()
    {
        $this->view('manager/manager_dashboard');
    }

    public function equipment_report()
    {
        $service = new M_Service();
        $data = [
            'services' => $service->findAll()
        ];

        $this->view('manager/equipment_report', $data);
    }

    public function equipment_upcoming_services()
    {
        $service = new M_Service();

        $upcomingServices = $service->getUpcomingServices();

        $data = [
            'services' => $upcomingServices
        ];

        $this->view('manager/equipment_upcoming_services', $data);
    }
    public function equipment_overdue_services()
    {
        $service = new M_Service();

        $upcomingServices = $service->getOverdueServices();

        $data = [
            'services' => $upcomingServices
        ];

        $this->view('manager/equipment_overdue_services', $data);
    }

    public function event_report()
    {
        $eventModel = new M_JoinEvent();
        $data['event_participants'] = $eventModel->getEventParticipantSummary();

        $this->view('manager/event_report', $data);
    }
    public function event_payment($event_id = null)
{
    // Initialize models
    $eventModel = new M_Event();
    $participantModel = new M_JoinEvent();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $event_id = $_POST['event_id'] ?? $event_id;

        // Prepare data
        $postData = [
            'event_id' => $event_id,
            'full_name' => $_POST['full_name'] ?? '',
            'nic' => $_POST['nic'] ?? '',
            'is_member' => isset($_POST['is_member']) ? 1 : 0,
            'membership_number' => $_POST['membership_number'] ?? null,
            'email' => $_POST['email'] ?? ''
        ];

        // Validate and save
        if ($participantModel->validate($postData)) {
            if ($participantModel->insert($postData)) {
                $_SESSION['success'] = "Participant added successfully!";

                // Ensure event_id is valid before redirecting
                if (!empty($event_id)) {
                    redirect('report/participant_details/' . $event_id);
                } else {
                    $_SESSION['error'] = "Missing event ID after save.";
                    redirect('report/event_payment');
                }
                return;
            } else {
                $_SESSION['error'] = "Failed to add participant. Please try again.";
            }
        } else {
            $_SESSION['form_errors'] = $participantModel->errors;
            $_SESSION['form_data'] = $postData;
        }

        // Redirect back to the form
        if (!empty($event_id)) {
            redirect('report/event_payment/' . $event_id);
        } else {
            redirect('report/event_payment');
        }

        return;
    }

    // For GET requests
    $event = $eventModel->first(['event_id' => $event_id]);

    $this->view('manager/event_payment', [
        'event' => $event,
        'event_id' => $event_id,
        'errors' => $_SESSION['form_errors'] ?? [],
        'old' => $_SESSION['form_data'] ?? []
    ]);

    // Clear session messages
    unset($_SESSION['form_errors']);
    unset($_SESSION['form_data']);
}

    public function participant_details($event_id)
    {
        $model = new M_JoinEvent();

        $data = [
            'event_id' => $event_id,
            'event_participants' => $model->where(
                ['event_id' => $event_id],
                [],
                'id'
            )
        ];

        $this->view('manager/participant_details', $data);
    }
    public function participant_update($id)
    {
        $model = new M_JoinEvent();

        $data = [
            'participant_id' => $id,
            'event_participants' => $model->where(
                ['participant_id' => $id],
                [],
                'id'
            )
        ];

        $this->view('manager/participant_details', $data);
    }
    public function payment_report()
    {
        $paymentModel = new M_Payment();
        $memberModel = new M_Member();

        // Get all members with their creation date
        $members = $memberModel->query("SELECT *, created_at as membership_start_date FROM member");

        // Get all payments grouped by member_id
        $allPayments = $paymentModel->paymentAdmin();
        $paymentsByMember = [];
        foreach ($allPayments as $payment) {
            $paymentsByMember[$payment->member_id][] = $payment;
        }

        // Define expected amounts and periods for each plan
        $planDetails = [
            'Monthly' => ['amount' => 2000, 'period' => '1 month'],
            'Quarterly' => ['amount' => 6000, 'period' => '3 months'],
            'Semi-Annually' => ['amount' => 12000, 'period' => '6 months'],
            'Annually' => ['amount' => 24000, 'period' => '1 year']
        ];

        // Process each member to check payment status
        $reportData = [];
        foreach ($members as $member) {
            $memberPayments = $paymentsByMember[$member->member_id] ?? [];
            $plan = $member->membership_plan;
            $expectedAmount = $planDetails[$plan]['amount'] ?? 0;
            $period = $planDetails[$plan]['period'] ?? '1 month';

            // Calculate payment status
            $totalPaid = 0;
            $lastPaymentDate = null;
            $lastValidDate = null;

            foreach ($memberPayments as $payment) {
                $paymentDate = strtotime($payment->created_at);
                $totalPaid += $payment->amount;
                if ($lastPaymentDate === null || $paymentDate > $lastPaymentDate) {
                    $lastPaymentDate = $paymentDate;
                    $lastValidDate = strtotime("+$period", $paymentDate);
                }
            }

            // If no payments, use membership start date
            if ($lastPaymentDate === null) {
                $lastPaymentDate = strtotime($member->membership_start_date);
                $lastValidDate = strtotime("+$period", $lastPaymentDate);
            }

            $isCompliant = (time() < $lastValidDate) && ($totalPaid >= $expectedAmount);

            $reportData[] = (object)[
                'member_id' => $member->member_id,
                'member_name' => $member->first_name . ' ' . $member->last_name,
                'membership_plan' => $plan,
                'membership_start_date' => date('Y-m-d', strtotime($member->membership_start_date)),
                'expected_amount' => $expectedAmount,
                'total_paid' => $totalPaid,
                'last_payment_date' => $lastPaymentDate ? date('Y-m-d', $lastPaymentDate) : 'Never',
                'last_valid_date' => date('Y-m-d', $lastValidDate),
                'is_compliant' => $isCompliant,
                'contact_number' => $member->contact_number,
                'email_address' => $member->email_address
            ];
        }

        $this->view('manager/payment_report', ['reportData' => $reportData]);
    }
}
