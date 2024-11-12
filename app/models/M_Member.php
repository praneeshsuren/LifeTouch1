<?php

    //Member class
    class M_Member{

        use Model;

        protected $table = 'member';
        protected $allowedColumns = [
            'member_id',
            'first_name',
            'last_name',
            'full_name',
            'date_of_birth',
            'age',
            'address',
            'height',
            'weight',
            'bmi_value',
            'contact_number',
            'gender',
            'email'
        ];

        
    }