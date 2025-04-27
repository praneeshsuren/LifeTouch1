<?php

use GrahamCampbell\ResultType\Success;

class Report extends Controller
{
    use Database;

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
        // In your controller
        $membershipReport = new M_MembershipSubscriptions();
        $data['reportData'] = $membershipReport->getMembershipReport();
        $this->view('manager/payment_report', $data);
    }
    public function physicalPayment_report()
    {
        // In your controller
        $membershipReport = new M_MembershipLatest();
        $data['reportData'] = $membershipReport->getMembershipReport();
        $this->view('manager/physicalPayment_report', $data);
    }

    public function income_report()
    {
        $equipmentModel = new M_Equipment();
        $serviceModel = new M_Service();
        $supplymentSaleseModel = new M_SupplementSales();
        $supplymentPurchaseModel = new M_SupplementPurchases();
        $membershipPaymentModel = new M_Payment();
        $eventPaymentModel = new M_EventPayment();

        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;


        $equipmentPurchaseSum = $equipmentModel->getEquipmentPurchaseSum($startDate, $endDate);
        $servicePurchaseSum = $serviceModel->getMonthlyServicePaymentSum($startDate, $endDate);
        $supplementSaleseSum = $supplymentSaleseModel->getSupplementSales($startDate, $endDate);
        $supplementPurchaseSum = $supplymentPurchaseModel->getSupplementPurchase($startDate, $endDate);
        $membershipPaymentSum = $membershipPaymentModel->getTotalPayment($startDate, $endDate);
        $eventPaymentSum = $eventPaymentModel->getTotalEventPayment($startDate, $endDate);

        // Total Income
        $totalIncome = $membershipPaymentSum + $eventPaymentSum + $supplementSaleseSum;

        // Total Expense
        $totalExpense = $supplementPurchaseSum  + $equipmentPurchaseSum + $servicePurchaseSum;



        $this->view('manager/income_report', [
            'equipmentPurchaseSum' => $equipmentPurchaseSum,
            'servicePurchaseSum' => $servicePurchaseSum,
            'supplementSaleseSum' => $supplementSaleseSum,
            'supplementPurchaseSum' => $supplementPurchaseSum,
            'membershipPaymentSum' => $membershipPaymentSum,
            'eventPaymentSum' => $eventPaymentSum,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense
        ]);
    }
}
