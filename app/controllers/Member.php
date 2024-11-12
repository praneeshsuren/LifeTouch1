<?php

    class Member extends Controller{
        
        public function index(){

            $member = new M_Member;

            // $arr['first_name'] = "Imeth";
            // $arr['last_name'] = "Methnuka";
            // $arr['date_of_birth'] = date("2002-06-20");
            // $arr['height'] = 1.83;
            // $arr['weight'] = 70.2;
            // $arr['gender'] = "Male";
            // $arr['email'] = "imethmethnuka@gmail.com";
            // $arr['address'] = "22, Old Quary Road, Colombo";
            // $arr['contact_number'] = 0771234123;

            $result = $member->findAll();
                 
            show($result);
            $this->view(('member/dashboard'));
        }

    }