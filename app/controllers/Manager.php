<?php

class Manager extends Controller
{

    public function index()
    {
        $this->view('manager/manager_dashboard');
    }

    public function announcement()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $announcement = new M_Announcement;
            if ($announcement->validate($_POST)) {

                $announcement->insert($_POST);
                redirect('manager/announcement_main');
            }
            $data['errors'] = $announcement->errors;
        }


        $this->view('manager/announcement', $data);
    }

    public function announcement_main()
    {
        $announcement = new M_Announcement();

        // Fetch all announcements
        $data = $announcement->findAll();
        $this->view('manager/announcement_main', ['data' => $data]);
    }

    public function announcement_update()
    {
        $this->view('manager/announcement_update');
    }
    public function announcement_read($id)
    {
        // Create an instance of M_Announcement model
        $announcement = new M_Announcement();

        // Prepare the where clause to get the specific announcement
        $arr['announcement_id'] = $id;

        // Fetch the result from the model
        $result = $announcement->where($arr);

        // Check if result is not empty
        if (!empty($result)) {
            // Pass the result to the view (announcement details)
            $data['announcement'] = $result[0];  // Assuming the result is an array and we're fetching the first record
        } else {
            // If no announcement is found, show a message or handle error
            $data['announcement'] = null;
        }

        // Display the view with the relevant announcement
        $this->view('manager/announcement_read', $data);
    }
    public function delete_announcement($id)
    {
        // Load the Announcement model
        $announcement = new M_Announcement();

        // Call the model's delete method with the correct column name
        if ($announcement->delete($id, 'announcement_id')) {
            // Redirect to the announcements page with a success message
            redirect('manager/announcement_main');
        } else {
            // Redirect to the announcements page with an error message
            redirect('manager/announcement_main?error=delete_failed');
        }
    }


    public function report()
    {
        $this->view('manager/report');
    }
    public function report_main()
    {
        $this->view('manager/report_main');
    }
    public function member()
    {
        $this->view('manager/member');
    }
    public function member_view()
    {
        $this->view('manager/member_view');
    }
    public function member_edit()
    {
        $this->view('manager/member_edit');
    }
    public function member_create()
    {
        $this->view('manager/member_create');
    }
    public function trainer()
    {
        $this->view('manager/trainer');
    }
    public function admin()
    {
        $this->view('manager/admin');
    }
    public function equipment()
    {
        $this->view('manager/equipment');
    }
}
