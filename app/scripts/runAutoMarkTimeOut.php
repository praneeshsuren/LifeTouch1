<?php
// Correct the path to include the AutoMarkTimeOut controller file
require_once  '/Applications/XAMPP/xamppfiles/htdocs/LifeTouch1/app/controllers/AutoMarkTimeOut.php';  // Added '/' for correct path

// Create an instance of the AutoMarkTimeOut controller
$controller = new AutoMarkTimeOut();

// Call the method to run the task
$controller->autoMarkTimeOut();
?>
