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
            $query = "SELECT user_id FROM users WHERE user_type LIKE 'MB%'";  // Fetch all members
            $users = $this->query($query);
            foreach ($users as $user) {
                $this->createNotification($user->user_id, $message, $userType);
            }
        }

        // Send a notification to all trainers (user_type starts with 'TN')
        public function notifyAllTrainers($message, $userType) {
            $query = "SELECT user_id FROM users WHERE user_type LIKE 'TN%'";  // Fetch all trainers
            $users = $this->query($query);
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
        
    }
?>