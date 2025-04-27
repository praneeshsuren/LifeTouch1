<?php

class Notification extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        // Instantiate the model in the constructor
        $this->notificationModel = new M_Notification();
    }

    // Display notifications to the user
    public function showNotifications($userId) {
        $notifications = $this->notificationModel->getNotifications($userId);
        include 'views/notifications_view.php'; // Pass notifications to the view
    }

    // Mark a notification as read
    public function markAsRead($notificationId) {
        $this->notificationModel->markAsRead($notificationId);
        header("Location: index.php?action=notifications"); // Redirect to notifications page
    }

    // Create a new notification for a user
    public function createNotification($userId, $message, $userType) {
        $this->notificationModel->createNotification($userId, $message, $userType);
        header("Location: index.php?action=notifications"); // Redirect to notifications page
    }

    // Send notification to all users
    public function notifyAllUsers($message, $userType) {
        $this->notificationModel->notifyAllUsers($message, $userType);
        header("Location: index.php?action=notifications"); // Redirect to notifications page
    }

    // Send notification to all members
    public function notifyAllMembers($message, $userType) {
        $this->notificationModel->notifyAllMembers($message, $userType);
        header("Location: index.php?action=notifications"); // Redirect to notifications page
    }

    // Send notification to all trainers
    public function notifyAllTrainers($message, $userType) {
        $this->notificationModel->notifyAllTrainers($message, $userType);
        header("Location: index.php?action=notifications"); // Redirect to notifications page
    }

    // Send notification to a specific user type
    public function notifyUserType($userType, $message) {
        // Only the user type and message are needed
        $this->notificationModel->notifyUserType($userType, $message);
        header("Location: index.php?action=notifications"); // Redirect to notifications page
    }
}

?>
