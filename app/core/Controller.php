<?php

    class Controller{

        public function view($name, $data = []){
            if(!empty($data)){
                extract($data);
            }
            $filename = "../app/views/pages/".$name.".view.php";
            if(file_exists($filename)){
                require $filename;
            }
            else{
                $filename = "../app/views/pages/404.view.php";
                require $filename;
            }
        }

        public function checkAuth($requiredRole = null) {
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
        }

        
    }