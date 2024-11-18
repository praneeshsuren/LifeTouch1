<?php

    function show($stuff){
        echo "<pre>";
        print_r($stuff);
        echo "</pre>";
    }

    function esc($str){
        return htmlspecialchars($str);
    }

    function redirect($path){
        header("Location: ".URLROOT."/".$path);
        die;
    }

    function calculateAge($date_of_birth) {
        $dob = new DateTime($date_of_birth);
        $today = new DateTime();
        $age = $today->diff($dob)->y; // Difference in years
        return $age;
    }