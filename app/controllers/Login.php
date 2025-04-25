<?php

class Login extends Controller
{

    public function index()
    {
        $this->view('home/home-login');
    }

    public function user()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check if username and password fields are set
            if (!isset($_POST['username']) || !isset($_POST['password']) || empty($_POST['username']) || empty($_POST['password'])) {
                $data['error'] = 'Username and password are required.';
                $this->view('home/home-login', $data);
                return;
            }

            $user = new M_User;
            $data = [
                'username' => trim($_POST['username']),
                'password' => $_POST['password']
            ];

            // Fetch the user details based on username
            $userDetails = $user->first(['username' => $data['username']]);

            if ($userDetails) {
                // Verify the password
                if (password_verify($data['password'], $userDetails->password)) {
                    // Store user_id in session
                    $_SESSION['user_id'] = $userDetails->user_id;

                    // Check if the user is a trainer or member based on the user_id prefix
                    $rolePrefix = substr($userDetails->user_id, 0, 2); // 'TN' for trainer, 'MB' for member

                    if ($rolePrefix === 'MB') {
                        // Fetch additional member details
                        $member = new M_Member;
                        $memberDetails = $member->findByMemberId($userDetails->user_id);

                        if ($memberDetails) {
                            // Store the member details in session
                            $_SESSION['role'] = 'Member';
                            $_SESSION['first_name'] = $memberDetails->first_name;
                            $_SESSION['last_name'] = $memberDetails->last_name;
                            $_SESSION['image'] = $memberDetails->image;
                            $_SESSION['last_activity'] = time();

                            // Redirect to member dashboard
                            redirect('member');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Member details not found.';
                            $this->view('home/home-login', $data);
                        }
                    } elseif ($rolePrefix === 'TN') {
                        // Fetch additional trainer details
                        $trainer = new M_Trainer;
                        $trainerDetails = $trainer->findByTrainerId($userDetails->user_id);

                        if ($trainerDetails) {
                            // Store the trainer details in session
                            $_SESSION['role'] = 'Trainer';
                            $_SESSION['first_name'] = $trainerDetails->first_name;
                            $_SESSION['last_name'] = $trainerDetails->last_name;
                            $_SESSION['image'] = $trainerDetails->image;

                            // Redirect to trainer dashboard
                            redirect('trainer');
                        } else {
                            // Handle the case where trainer details are not found
                            $data['error'] = 'Trainer details not found.';
                            $this->view('home/home-login', $data);
                        }
                    } elseif ($rolePrefix === 'MR') {
                        // Fetch additional member details
                        $manager = new M_Manager;
                        $managerDetails = $manager->findByManagerId($userDetails->user_id);

                        if ($managerDetails) {
                            // Store the member details in session
                            $_SESSION['role'] = 'Manager';
                            $_SESSION['first_name'] = $managerDetails->first_name;
                            $_SESSION['last_name'] = $managerDetails->last_name;
                            $_SESSION['image'] = $managerDetails->image;

                            // Redirect to member dashboard
                            redirect('manager');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Manager details not found.';
                            $this->view('home/home-login', $data);
                        }
                    } elseif ($rolePrefix === 'RT') {
                        // Fetch additional member details
                        $receptionist = new M_Receptionist;
                        $receptionistDetails = $receptionist->findByReceptionistId($userDetails->user_id);

                        if ($receptionistDetails) {
                            // Store the member details in session
                            $_SESSION['role'] = 'Receptionist';
                            $_SESSION['first_name'] = $receptionistDetails->first_name;
                            $_SESSION['last_name'] = $receptionistDetails->last_name;
                            $_SESSION['image'] = $receptionistDetails->image;

                            // Redirect to member dashboard
                            redirect('receptionist');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Receptionist details not found.';
                            $this->view('home/home-login', $data);
                        }
                    } elseif ($rolePrefix === 'AD') {
                        // Fetch additional member details
                        $admin = new M_Admin;
                        $adminDetails = $admin->findByAdminId($userDetails->user_id);

                        if ($adminDetails) {
                            // Store the member details in session
                            $_SESSION['role'] = 'Admin';
                            $_SESSION['first_name'] = $adminDetails->first_name;
                            $_SESSION['last_name'] = $adminDetails->last_name;
                            $_SESSION['image'] = $adminDetails->image;

                            // Redirect to member dashboard
                            redirect('admin');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Admin details not found.';
                            $this->view('home/home-login', $data);
                        }
                    } else {
                        // Handle invalid user role prefix
                        $data['error'] = 'Invalid user role.';
                        $this->view('home/home-login', $data);
                    }
                } else {
                    // Handle invalid password
                    $data['error'] = 'Invalid username or password.';
                    $this->view('home/home-login', $data);
                }
            } else {
                // Handle user not found
                $data['error'] = 'Invalid username or password.';
                $this->view('home/home-login', $data);
            }
        } else {
            // Redirect to the login page if the request is not POST
            redirect('login');
        }
    }

    public function logout()
    {
        // Destroy the session
        session_destroy();
        // Redirect to login page with a success message
        $_SESSION['message'] = 'You have been logged out successfully.';
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
