<?php
    class Admin extends Controller{

        public function index(){
            $this->view('admin/admin-dashboard');
        }

        public function announcement(){
            $this->view('admin/admin-announcement');
        }

    }