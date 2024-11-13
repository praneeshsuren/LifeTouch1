<?php

class Manager extends Controller
{

    public function index()
    {
        $this->view('manager/manager_dashboard');
    }

    public function announcement()
    {
        $this->view('manager/announcement');
    }
    public function announcement_main()
    {
        $this->view('manager/announcement_main');
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
