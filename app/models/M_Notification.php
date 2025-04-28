<?php
    class M_Notification{
        use Model;

        protected $table = 'notifications';
        protected $allowedColumns = [
            'id',
            'user_id',
            'message',
            'user_type',
            'is_read',
            'created_at',
            'updated_at'
        ];

        // Get notifications for a specific user
        public function getNotifications($userId) {
            $query = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC";
            $data = [':user_id' => $userId];
            return $this->query($query, $data);
        }

        // Mark a notification as read
        public function markAsRead($notificationId) {
            $query = "UPDATE notifications SET is_read = 1 WHERE id = :notification_id";
            $data = [':notification_id' => $notificationId];
            return $this->query($query, $data);
        }

        // Add a new notification
        public function createNotification($userId, $message, $userType) {
            $query = "INSERT INTO notifications (user_id, message, user_type) VALUES (:user_id, :message, :user_type)";
            $data = [
                ':user_id' => $userId,
                ':message' => $message,
                ':user_type' => $userType
            ];
            return $this->query($query, $data);
        }

        // Send a notification to all users
        public function notifyAllUsers($message, $userType) {
            $query = "SELECT user_id FROM users";  // Fetch all user IDs
            $users = $this->query($query);
            foreach ($users as $user) {
                $this->createNotification($user->user_id, $message, $userType);
            }
        }

        // Send a notification to all members (user_type starts with 'MB')
        public function notifyAllMembers($message, $userType) {
            $query = "SELECT user_id FROM users WHERE user_id LIKE 'MB%'";  // Fetch all members
            $users = $this->query($query);
            foreach ($users as $user) {
                $this->createNotification($user->user_id, $message, $userType);
            }
        }

        // Send a notification to all trainers (user_type starts with 'TN')
        public function notifyAllTrainers($message, $userType) {
            $query = "SELECT user_id FROM users WHERE user_id LIKE 'TN%'";  // Fetch all trainers
            $users = $this->query($query);
            foreach ($users as $user) {
                $this->createNotification($user->user_id, $message, $userType);
            }
        }

        public function notifyAllManagers($message) {
            $query = "SELECT user_id FROM users WHERE user_id LIKE 'MR%'";  // Fetch all managers
            $users = $this->query($query);
            $userType = "Manager";
            foreach ($users as $user) {
                $this->createNotification($user->user_id, $message, $userType);
            }
        }

        // Send a notification to a specific user type
        public function notifyUserType($userType, $message) {
            $query = "SELECT user_id FROM users WHERE user_type = :user_type";  // Fetch users by type
            $data = [':user_type' => $userType];
            $users = $this->query($query, $data);
            foreach ($users as $user) {
                $this->createNotification($user->user_id, $message, $userType); // Use $userType to specify the recipient type
            }
        }

        // Mark all notifications as read for a specific user
        public function markAllAsRead($userId) {
            $query = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id AND is_read = 0"; // Only mark unread notifications as read
            $data = [':user_id' => $userId];
            return $this->query($query, $data);
        }

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
                    if ($membershipUpdated && $memberUpdated) {
                        // Send notification to the specific member
                        $this->sendNotificationToMember($membership->member_id, $message);
                    } else {
                        // Log an error or handle it appropriately if the update failed
                        error_log("Failed to update membership status for member ID {$membership->member_id}");
                    }
                }
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
            }
        }
        
    
        public function sendNotificationToMember($memberId, $message) {
            // Assuming you have a Notification Model like M_Notification
            $notificationModel = new M_Notification();
    
            // Send notification to the specific member
            $notificationModel->createNotification($memberId, $message, 'Member');
        }

        
    }
?>