<?php
// Correct the path to include the AutoMarkTimeOut controller file
require_once '../core/init.php'; 
require_once '../controllers/AutoMarkTimeOut.php';
require_once '../core/App.php';

// Create an instance of the AutoMarkTimeOut controller
$controller = new AutoMarkTimeOut();

// Call the method to run the task
$controller->autoMarkTimeOut();
?>
