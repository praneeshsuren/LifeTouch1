<?php

    class Trainer extends Controller{

        public function __construct() {
            // Check if the user is logged in as a trainer
            $this->checkAuth('trainer');
        }
        
        public function index(){
            $this->view('trainer/trainer-dashboard');
        }

        public function announcements(){
            $this->view('trainer/trainer-announcements');
        }

    }