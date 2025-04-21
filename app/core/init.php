<?php

// spl_autoload_register(function ($className) {
//     require $filename = APPROOT . "/models/" . ucfirst($className) . ".php";
// });

// require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
// $dotenv->load();

// require 'config.php';



// require 'config_session.php';
// require 'functions.php';
// require 'Database.php';
// require 'Model.php';
// require 'Controller.php';
// require 'App.php';
// Define APPROOT first, if not already defined

//define('APPROOT', dirname(dirname(__FILE__))); 

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2)); 
$dotenv->load();
foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

require 'config.php';

spl_autoload_register(function ($className) {
    require $filename = APPROOT . "/models/" . ucfirst($className) . ".php";
});

//require 'config_session.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';


