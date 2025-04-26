<?php

class Controller {

    public function view($name, $data = []) {
        if (!empty($data)) {
            extract($data);
        }
        $filename = "../app/views/pages/" . $name . ".view.php";
        if (file_exists($filename)) {
            require $filename;
        } else {
            require "../app/views/pages/404.view.php";
        }
    }

    public function checkAuth($requiredRole = null) {
        $this->checkSessionTimeout();

        if (!isset($_SESSION['user_id'])) {
            // User is not logged in
            $_SESSION['error'] = 'Unauthorized access. Redirecting to login page.';
            redirect('login');
        }

        if ($requiredRole && $_SESSION['role'] !== $requiredRole) {
            // User role does not match the required role
            $_SESSION['error'] = 'Unauthorized access for this role.';
            redirect('login');
        }

        // ✅ If user is valid and role is okay, update last activity
        $_SESSION['last_activity'] = time();
    }

    public function checkSessionTimeout() {
        $timeoutDuration = 1800; // 30 minutes

        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeoutDuration)) {
            // Session expired
            session_unset();
            session_destroy();
            $_SESSION['error'] = 'Session expired due to inactivity.';
            redirect('login');
        }
    }
}
