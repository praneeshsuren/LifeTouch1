<?php

    //User class
    class M_User{

        use Model;

        protected $table = 'user';
        protected $allowedColumns = [
            'user_id',
            'username',
            'password'
        ];
    }