<?php

class Home extends Controller
{
    public function index()
    {
        $this->view('home/home-login');
    }
    public function homepage()
    {
        $this->view('home/index');
    }
}

