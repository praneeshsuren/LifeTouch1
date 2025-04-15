<?php

    // Database Configurations
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'LifeTouch');

    // APP ROOT
    define('APPROOT', dirname(dirname(__FILE__)));

    // URL ROOT
    if($_SERVER['SERVER_NAME'] == 'localhost'){
        define('URLROOT', 'http://localhost/LifeTouch1/public');
    }
    else{
        define('URLROOT', 'https://www.lifetouch.lk'); 
    }

    // WEBSITE NAME
    define('APP_NAME', 'LifeTouch');

    //True means show errors
    define('DEBUG', true);

    // payment
    define('STRIPE_SECRET_KEY', 'sk_test_51RE1fRQm7OGeuaUjvHNYOt94DQnEOLBVrTsqIUvxlaXymLZkpMN6Kl1YjpWDGfoPUSDAZDFwFgUjqzHHR4e5swnS00GaB0i6iF');