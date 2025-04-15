<?php

spl_autoload_register(function ($className) {

    require $filename = APPROOT . "/models/" . ucfirst($className) . ".php";
});

require 'config.php';
require_once dirname(__DIR__, 2) . '/vendor/autoload.php';
require 'config_session.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
