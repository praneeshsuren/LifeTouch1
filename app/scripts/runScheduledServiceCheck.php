<?php
// scheduled_service_check.php
require_once '../core/init.php'; 
require_once '../controllers/Service.php';
require_once '../core/App.php';

// Instantiate the controller and invoke the function to send notifications
$controller = new Service();
$controller->equipmentServiceDate();  // This will check overdue services and send notifications
?>
