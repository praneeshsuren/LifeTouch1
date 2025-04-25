<?php

class Report extends Controller
{
    use Database;

    public function index()
    {
        $this->view('manager/manager_dashboard');
    }
    public function findAll()
    {
        
        $sql = "SELECT s.*, e.name AS equipment_name 
                FROM service AS s  
                LEFT JOIN equipment AS e ON s.equipment_id = e.equipment_id";
    
        return $this->query($sql);
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
