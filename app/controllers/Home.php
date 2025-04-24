<?php

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

            $data = [
                'event_id'      => $_POST['event_id'],
                'full_name'     => trim($_POST['full_name']),
                'nic'           => trim($_POST['nic']),
                'is_member'     => isset($_POST['is_member']) ? 1:0,
                'membership_number' => $_POST['membership_number'] ?? null,
                'email'    => trim($_POST['email']),
                
            ];
           
           // ACTUALLY PERFORM VALIDATION
        if ($joinModel->validate($data)) {
            if ($joinModel->insert($data)) {
                // success
                redirect('home?scroll=plans');
            } else {
                $_SESSION['join_errors'] = ['database' => 'Failed to save registration'];
                $_SESSION['form_data'] = $data;
                redirect('home?scroll=plans');
            }
        } else {
            // Store validation errors from model
            $_SESSION['join_errors'] = $joinModel->getErrors();
            $_SESSION['form_data'] = $data;
            redirect('home?scroll=plans');
        }
    } else {
        redirect('home?scroll=plans');
    }
}

public function checkout(){
    $this->view('home/checkout');
}
}