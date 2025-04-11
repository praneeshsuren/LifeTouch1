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
                'contact_no'    => trim($_POST['contact_no']),
                
            ];
           
           if (empty($errors)) {
               if ($joinModel->insert($data)) {
                   // success
                   redirect('home?scroll=plans');
               } else {
                   die('Database insert failed.');
               }
           } else {
               // you can store errors in session and display on page
               $_SESSION['join_errors'] = $errors;
               $_SESSION['form_data'] = $data;
               redirect('home');

           }
       } else {
           redirect('home');

       }
   }
}