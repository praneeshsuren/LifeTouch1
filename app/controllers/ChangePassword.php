<?php
class ChangePassword extends Controller
{
    public function index()
    {
        $this->view('changePassword');
    }

    public function updatePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentPassword = trim($_POST['current_password']);
            $newPassword = trim($_POST['new_password']);

            $userId = $_SESSION['user_id'];

            $userModel = new M_User;

            $user = $userModel->findByUserId($userId);

            if (!$user || !password_verify($currentPassword, $user->password)) {
                $_SESSION['error'] = 'Current password is incorrect.';
                header('Location: ' . URLROOT . '/ChangePassword');
                exit;
            }

            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $success = $userModel->updatePassword($userId, $hashedNewPassword);

            if (!$success) {

                $successMessage = 'Password updated successfully! Please log in again.';
                // Log the user out after password change
                session_unset();
                session_destroy();
                
                session_start();
                session_regenerate_id(true); // extra security
                $_SESSION['success'] = $successMessage;

                redirect('login');
            } else {
                $_SESSION['error'] = 'Something went wrong. Please try again.';
            }

            header('Location: ' . URLROOT . '/ChangePassword');
            exit;
        }
    }
    
}
?>
