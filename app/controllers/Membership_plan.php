<?php

class Membership_plan extends Controller
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
    public function plan_create()
    {
    
        $this->view('manager/plan_create');
    }
    
    
    
}
