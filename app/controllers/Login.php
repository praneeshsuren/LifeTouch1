<?php

class Login extends Controller
{

    public function index()
    {
        $this->view('home/home-login');
    }

    public function user()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }

        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            return $this->view('home/home-login', ['error' => 'Username and password are required.']);
        }

        $userModel = new M_User;
        $userDetails = $userModel->first(['username' => $username]);

        if (!$userDetails || !password_verify($password, $userDetails->password)) {
            return $this->view('home/home-login', ['error' => 'Invalid username or password.']);
        }

        // Securely reset session before login
        session_unset();
        session_destroy();
        session_start();
        session_regenerate_id(true);

        $_SESSION['user_id'] = $userDetails->user_id;
        $_SESSION['last_activity'] = time();

        $rolePrefix = substr($userDetails->user_id, 0, 2);

        $roles = [
            'MB' => ['Member', 'M_Member', 'findByMemberId', 'member'],
            'TN' => ['Trainer', 'M_Trainer', 'findByTrainerId', 'trainer'],
            'MR' => ['Manager', 'M_Manager', 'findByManagerId', 'manager'],
            'RT' => ['Receptionist', 'M_Receptionist', 'findByReceptionistId', 'receptionist'],
            'AD' => ['Admin', 'M_Admin', 'findByAdminId', 'admin']
        ];

        if (!array_key_exists($rolePrefix, $roles)) {
            return $this->view('home/home-login', ['error' => 'Invalid user role.']);
        }

        list($role, $modelClass, $findMethod, $redirectPath) = $roles[$rolePrefix];

        $model = new $modelClass;
        $details = $model->$findMethod($userDetails->user_id);

        if (!$details) {
            return $this->view('home/home-login', ['error' => "$role details not found."]);
        }

        $_SESSION['role'] = $role;
        $_SESSION['first_name'] = $details->first_name;
        $_SESSION['last_name'] = $details->last_name;
        $_SESSION['image'] = $details->image;

        redirect($redirectPath);
    }


    public function logout()
    {
        session_destroy();
        session_start();
        $_SESSION['success'] = 'You have been logged out successfully.';
        redirect('login');
    }

    public function requestReset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get JSON data from request
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
    
            $username = $data['username'] ?? '';
    
            // Initialize models
            $userModel = new M_User();
            $memberModel = new M_Member();
            $trainerModel = new M_Trainer();
            $adminModel = new M_Admin();
            $managerModel = new M_Manager();
            $receptionistModel = new M_Receptionist();
    
            // Check if username exists in users table
            $user = $userModel->first(['username' => $username]);
            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'Username not found']);
                return;
            }
    
            // Get user details based on user_id and role
            $email = null;
            $userType = null;
            $userId = $user->user_id;
    
            // Check each table for the user
            if ($member = $memberModel->first(['member_id' => $userId])) {
                $email = $member->email_address;
                $userType = 'member';
            } elseif ($trainer = $trainerModel->first(['trainer_id' => $userId])) {
                $email = $trainer->email_address;
                $userType = 'trainer';
            } elseif ($admin = $adminModel->first(['admin_id' => $userId])) {
                $email = $admin->email_address;
                $userType = 'admin';
            } elseif ($manager = $managerModel->first(['manager_id' => $userId])) {
                $email = $manager->email_address;
                $userType = 'manager';
            } elseif ($receptionist = $receptionistModel->first(['receptionist_id' => $userId])) {
                $email = $receptionist->email_address;
                $userType = 'receptionist';
            }
    
            if (!$email) {
                echo json_encode(['success' => false, 'message' => 'Email address not found for this user']);
                return;
            }
    
            // Generate reset token (you'll need to implement this)
            $resetToken = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
            // Save token to database (you'll need a password_reset_tokens table)
            $this->saveResetToken($userId, $resetToken, $expires);
    
            // Send email (implement this function)
            $this->sendResetEmail($email, $resetToken, $userType);
    
            echo json_encode(['success' => true, 'message' => 'Password reset link has been sent to your email']);
        }
    }
    
    private function saveResetToken($userId, $token, $expires) {
        // Create a password_reset_tokens table if you haven't already
        // Columns: id, user_id, token, expires_at, used (boolean)
        $db = new Database();
        $db->query("INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
        $db->bind(':user_id', $userId);
        $db->bind(':token', $token);
        $db->bind(':expires_at', $expires);
        return $db->execute();
    }
    
    private function sendResetEmail($email, $token, $userType) {
        $resetLink = URLROOT . "/login/resetPassword?token=$token&type=$userType";
        
        $subject = "Password Reset Request";
        $message = "You have requested to reset your password. Click the link below to proceed:\n\n";
        $message .= $resetLink . "\n\n";
        $message .= "This link will expire in 1 hour.\n";
        $message .= "If you didn't request this, please ignore this email.";
        
        // Use your preferred email sending method here
        mail($email, $subject, $message);
    }
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        $userType = $_GET['type'] ?? '';
    
        // Verify token
        $db = new Database();
        $db->query("SELECT * FROM password_reset_tokens WHERE token = :token AND used = 0 AND expires_at > NOW()");
        $db->bind(':token', $token);
        $tokenData = $db->single();
    
        if (!$tokenData) {
            $this->view('login/reset_password', ['error' => 'Invalid or expired token']);
            return;
        }
    
        // Show reset form
        $this->view('login/reset_password', ['token' => $token, 'userType' => $userType]);
    }
    
    public function processReset() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $userType = $_POST['userType'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
    
            // Validate passwords match
            if ($password !== $confirm_password) {
                $this->view('login/reset_password', [
                    'token' => $token,
                    'userType' => $userType,
                    'error' => 'Passwords do not match'
                ]);
                return;
            }
    
            // Verify token again
            $db = new Database();
            $db->query("SELECT * FROM password_reset_tokens WHERE token = :token AND used = 0 AND expires_at > NOW()");
            $db->bind(':token', $token);
            $tokenData = $db->single();
    
            if (!$tokenData) {
                $this->view('login/reset_password', ['error' => 'Invalid or expired token']);
                return;
            }
    
            // Update password in users table
            $userModel = new M_User();
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $userModel->update($tokenData->user_id, ['password' => $hashedPassword], 'user_id');
    
            // Mark token as used
            $db->query("UPDATE password_reset_tokens SET used = 1 WHERE token = :token");
            $db->bind(':token', $token);
            $db->execute();
    
            // Redirect to login with success message
            $_SESSION['success'] = 'Password has been reset successfully. Please login with your new password.';
            redirect('login');
        }
    }
    
}
