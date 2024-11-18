<?php

class Login extends Controller {

    public function index() {
        // Load the login view
        $this->view('home/home-login');
    }

    public function user() {
        // Start session at the beginning of the script
        session_start();
        
        // Check if username and password are posted
        if (isset($_POST['username'], $_POST['password'])) {
            $user = new M_User;
            $data = [
                'username' => $_POST['username'],
                'password' => $_POST['password']
            ];
    
            // Fetch the user details based on username and password
            $userDetails = $user->first(['username' => $data['username']]);
    
            if ($userDetails) {
                // Store user_id in session
                $_SESSION['user_id'] = $userDetails->user_id;
    
                // Check if the user is a trainer or member based on the user_id prefix
                $rolePrefix = substr($userDetails->user_id, 0, 2); // 'TN' for trainer, 'MB' for member
    
                if ($rolePrefix === 'TN') {
                    // Fetch additional trainer details
                    $trainer = new M_Trainer;
                    $trainerDetails = $trainer->findByTrainerId($userDetails->user_id);
                    
                    if ($trainerDetails) {
                        // Store the trainer details in session
                        $_SESSION['role'] = 'trainer';
                        $_SESSION['first_name'] = $trainerDetails->first_name;
                        $_SESSION['last_name'] = $trainerDetails->last_name;
    
                        // Redirect to trainer dashboard
                        redirect('trainer');
                    } else {
                        // Handle the case where trainer details are not found
                        $data['error'] = 'Trainer details not found';
                        $this->view('home/home-login', $data);
                    }
                } elseif ($rolePrefix === 'MB') {
                    // Fetch additional member details
                    $member = new M_Member;
                    $memberDetails = $member->findByMemberId($userDetails->user_id);
                    
                    if ($memberDetails) {
                        // Store the member details in session
                        $_SESSION['role'] = 'member';
                        $_SESSION['first_name'] = $memberDetails->first_name;
                        $_SESSION['last_name'] = $memberDetails->last_name;
    
                        // Redirect to member dashboard
                        redirect('member');
                    } else {
                        // Handle the case where member details are not found
                        $data['error'] = 'Member details not found';
                        $this->view('home/home-login', $data);
                    }
                } else {
                    // Handle invalid user role prefix
                    $data['error'] = 'Invalid user role';
                    $this->view('home/home-login', $data);
                }
            } 
            else {
                // Handle invalid login: incorrect username or password
                $data['error'] = 'Invalid username or password';
                $this->view('home/home-login', $data);
            }
        } else {
            // Handle missing login details (if the form is empty or not submitted)
            $data['error'] = 'Username and password are required';
            $this->view('home/home-login', $data);
        }
    }
}
