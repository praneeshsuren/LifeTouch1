<?php

class Notification extends Controller
{
    public function equipmentOverdueServiceDateNotifications(){
        // Instantiate the service model
        $serviceModel = new M_Service;
    
        // Fetch all overdue services
        $overdueServices = $serviceModel->getOverdueServices();
        if ($overdueServices) {
            // Loop through each overdue service and send a notification to the manager
            foreach ($overdueServices as $service) {
                // Prepare the notification message
                $message = "The service for equipment: '{$service->equipment_name}' is overdue. Service was due on: {$service->next_service_date}.";
        
                // Send notification to the manager (using a function like createNotification)
                $this->sendNotificationToManager($message);
            }
        } else {
            echo "No overdue services found.";
        }
    }
    
    // Create a method to send the notification
    public function sendNotificationToManager($message) {
        $notificationModel = new M_Notification();
        $notificationModel->notifyAllManagers($message);
    }

    // In your Controller or another appropriate class

    public function membershipExpiryNotifications() {
        // Instantiate the membership model
        $membershipModel = new M_MembershipSubscriptions();
        $memberModel = new M_Member();
        
        // Fetch all expired memberships
        $expiredMemberships = $membershipModel->getExpiredMemberships();
        
        // Check if any expired memberships are found
        if ($expiredMemberships && is_array($expiredMemberships)) {
            // Loop through expired memberships and send notifications
            foreach ($expiredMemberships as $membership) {
                // Prepare the notification message
                $message = "Dear member, your {$membership->plan} membership plan has expired on {$membership->end_date}. Please renew your subscription to continue enjoying our services.";
                
                // Update the membership status to 'inactive'
                $membershipUpdated = $membershipModel->updateMembershipStatus($membership->id, 'inactive');
                
                // Update the member status to 'inactive'
                $memberUpdated = $memberModel->updateMembershipStatus($membership->member_id, 'Inactive');
                
                // Check if the updates were successful before sending the notification
                if (!$membershipUpdated && !$memberUpdated) {
                    // Send notification to the specific member
                    $this->sendNotificationToMember($membership->member_id, $message);
                } else {
                    // Log an error or handle it appropriately if the update failed
                    error_log("Failed to update membership status for member ID {$membership->member_id}");
                }
            }
        } else {
            // Handle the case where no expired memberships are found
            echo "No expired memberships found.";
        }
    }
    

    public function membershipExpiryNotificationsBeforeExpire() {
        // Instantiate the membership model
        $membershipModel = new M_MembershipSubscriptions();
        
        // Fetch all memberships that will expire in the next 1 or 2 days
        $expiringMemberships = $membershipModel->getExpiringMemberships();
        
        // Check if any expiring memberships are found
        if ($expiringMemberships && is_array($expiringMemberships)) {
            // Loop through the memberships and send notifications
            foreach ($expiringMemberships as $membership) {
                // Prepare the notification message
                $message = "Dear member, your {$membership->plan} membership plan will expire on {$membership->end_date}. Please renew your subscription to continue enjoying our services.";
                
                // Send notification to the specific member
                $this->sendNotificationToMember($membership->member_id, $message);
            }
        } else {
            // Handle the case where no expiring memberships are found
            echo "No memberships expiring in the next 1 or 2 days.";
        }
    }
    

    public function sendNotificationToMember($memberId, $message) {
        // Assuming you have a Notification Model like M_Notification
        $notificationModel = new M_Notification();

        // Send notification to the specific member
        $notificationModel->createNotification($memberId, $message, 'Member');
    }

    public function findDate(){
        date_default_timezone_set('Asia/Colombo');

        $currentDate = date('Y-m-d'); // Today's date
        $oneDayBefore = date('Y-m-d', strtotime('+1 day')); // 1 day before expiry
        $twoDaysBefore = date('Y-m-d', strtotime('+2 day'));

        echo "Current Date: $currentDate\n";
        echo "One Day After: $oneDayBefore\n";
        echo "Two Days After: $twoDaysBefore\n";
    }

    public function markAsRead($notificationId) {
        // Instantiate the notification model
        $notificationModel = new M_Notification();
        
        // Mark the notification as read
        $result = $notificationModel->markAsRead($notificationId);
        
        if ($result) {
            echo "Notification marked as read.";
            // Redirect back to the same page
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
        exit;
        } else {
            echo "Failed to mark notification as read.";
            // Redirect back to the same page
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
        exit;
        }
    }

    public function markAllAsRead($memberId) {
        // Instantiate the notification model
        $notificationModel = new M_Notification();
        
        // Mark all notifications as read for the member
        $result = $notificationModel->markAllAsRead($memberId);
        
        if ($result) {
            echo "All notifications marked as read.";
            // Redirect back to the same page
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
        exit;
        } else {
            echo "Failed to mark all notifications as read.";
            // Redirect back to the same page
        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
        exit;
        }
    }

}

?>
