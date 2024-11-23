<?php

    class Member extends Controller{


        
        public function index(){
            $this->view(('member/member-dashboard'));
        }
        public function memberViewtrainer(){
            $this->view('member/member-viewtrainer');
        }
        public function memberViewtrainerViewbtn(){
            $this->view('member/member-viewtrainer-viewbtn');
        }
        public function memberAnnouncements(){
            $this->view('member/member-announcements');
        }
        public function memberSupplements(){
            $this->view('member/member-supplements');
        }
        public function memberWorkoutschedules(){
            $this->view('member/member-workoutschedules');
        } 
        public function memberPayment(){
            $this->view('member/member-payment');
        } 
        public function memberSettings(){
            $this->view('member/member-settings');
        }
        public function memberTrainerbooking(){
            $this->view('member/member-trainerbooking');
        } 
    }