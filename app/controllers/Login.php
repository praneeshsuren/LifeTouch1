<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require dirname(__DIR__, 2) . '/vendor/autoload.php';

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

    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);

            if (empty($username)) {
                $this->view('home/forgot_password', ['error' => 'Please enter your username']);
                return;
            }

            $userModel = new M_User();
            $user = $userModel->first(['username' => $username]);

            if (!$user) {
                $this->view('home/forgot_password', ['error' => 'Username not found']);
                return;
            }

            // Get user's email from appropriate table
            $email = $this->getUserEmail($user->user_id);

            if (!$email) {
                $this->view('home/forgot_password', ['error' => 'Email address not found for this user']);
                return;
            }

            // Generate and save reset token
            $resetToken = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            if (!$this->saveResetToken($user->user_id, $resetToken, $expires)) {
                $this->view('home/forgot_password', ['error' => 'Failed to generate reset token']);
                return;
            }

            // Send reset email
            if ($this->sendResetEmail($email, $resetToken)) {
                $this->view('home/forgot_password', ['success' => 'Password reset link has been sent to your email']);
            } else {
                $this->view('home/forgot_password', ['error' => 'Failed to send reset email']);
            }
        } else {
            $this->view('home/forgot_password');
        }
    }

    private function getUserEmail($userId)
    {
        $prefix = substr($userId, 0, 2);

        switch ($prefix) {
            case 'MB': // Member
                $model = new M_Member();
                $field = 'member_id';
                break;
            case 'TN': // Trainer
                $model = new M_Trainer();
                $field = 'trainer_id';
                break;
            case 'AD': // Admin
                $model = new M_Admin();
                $field = 'admin_id';
                break;
            case 'MR': // Manager
                $model = new M_Manager();
                $field = 'manager_id';
                break;
            case 'RT': // Receptionist
                $model = new M_Receptionist();
                $field = 'receptionist_id';
                break;
            default:
                return null;
        }

        $user = $model->first([$field => $userId]);
        return $user->email_address ?? null;
    }

    use Database; 


    private function saveResetToken($userId, $token, $expires)
    {
        // No need to instantiate Database - just use the methods directly
        // Delete any existing tokens for this user
        $this->query("DELETE FROM password_reset_tokens WHERE user_id = :user_id", [':user_id' => $userId]);

        // Insert new token
        return $this->query(
            "INSERT INTO password_reset_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)",
            [
                ':user_id' => $userId,
                ':token' => $token,
                ':expires_at' => $expires
            ]
        );
    }

    public function resetPassword()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validate inputs
        if (empty($token) || empty($password) || empty($confirm_password)) {
            $this->view('home/reset_password', [
                'error' => 'All fields are required',
                'token' => $token
            ]);
            return;
        }

        if ($password !== $confirm_password) {
            $this->view('home/reset_password', [
                'error' => 'Passwords do not match',
                'token' => $token
            ]);
            return;
        }

        if (strlen($password) < 8) {
            $this->view('home/reset_password', [
                'error' => 'Password must be at least 8 characters',
                'token' => $token
            ]);
            return;
        }

        // Verify token and get user ID
        $tokenData = $this->get_row(
            "SELECT * FROM password_reset_tokens 
             WHERE token = :token AND used = 0 AND expires_at > NOW()",
            [':token' => $token]
        );

        if (!$tokenData) {
            $this->view('home/reset_password', [
                'error' => 'Invalid or expired token'
            ]);
            return;
        }

        // Update user password
        $userModel = new M_User();
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $updateResult = $userModel->updatePassword($tokenData->user_id, $hashedPassword);

        if ($updateResult) {
            // Mark token as used
            $this->query(
                "UPDATE password_reset_tokens SET used = 1 WHERE token = :token",
                [':token' => $token]
            );

            // Redirect to login page or a success page
            header("Location: /LifeTouch1/public/login?success=Password reset successfully. Please log in.");
            exit; // Make sure the redirect happens and no further code executes
        } else {
            $this->view('home/reset_password', [
                'error' => 'Failed to update password',
                'token' => $token
            ]);
        }
    } else {
        // Show form for password reset
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $this->view('home/reset_password', ['error' => 'Invalid reset link']);
            return;
        }

        $tokenData = $this->get_row(
            "SELECT * FROM password_reset_tokens 
             WHERE token = :token AND used = 0 AND expires_at > NOW()",
            [':token' => $token]
        );

        if (!$tokenData) {
            $this->view('home/reset_password', ['error' => 'Invalid or expired token']);
            return;
        }

        $this->view('home/reset_password', ['token' => $token]);
    }
}

    


    private function sendResetEmail($toEmail, $token)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'amandanethmini100@gmail.com';
            $mail->Password   = 'niib zlpx xskb bmag'; // Use App Password if 2FA enabled
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('amandanethmini100@gmail.com', APP_NAME);
            $mail->addAddress($toEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request - ' . APP_NAME;
            $mail->Body    = "Click here to reset your password: <a href='" . URLROOT . "/login/resetPassword?token=$token'>Reset Password</a>";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
    

}
