<?php

spl_autoload_register(function ($className) {

    require $filename = APPROOT . "/models/" . ucfirst($className) . ".php";
});

require 'config.php';
//require 'config_session.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
