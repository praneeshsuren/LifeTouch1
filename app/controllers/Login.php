<?php

class Login extends Controller {

    public function index() {
        $this->view('home/home-login');
    }

    public function user() {
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
                            $_SESSION['role'] = 'member';
                            $_SESSION['first_name'] = $memberDetails->first_name;
                            $_SESSION['last_name'] = $memberDetails->last_name;
                            $_SESSION['member_id'] = $memberDetails->member_id; // Store member_id in session

                            // Redirect to member dashboard
                            redirect('member');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Member details not found.';
                            $this->view('home/home-login', $data);
                        }
                    }

                    elseif ($rolePrefix === 'TN') {
                        // Fetch additional trainer details
                        $trainer = new M_Trainer;
                        $trainerDetails = $trainer->findByTrainerId($userDetails->user_id);

                        if ($trainerDetails) {
                            // Store the trainer details in session
                            $_SESSION['role'] = 'trainer';
                            $_SESSION['first_name'] = $trainerDetails->first_name;
                            $_SESSION['last_name'] = $trainerDetails->last_name;
                            $_SESSION['trainer_id'] = $trainerDetails->trainer_id;

                            // Redirect to trainer dashboard
                            redirect('trainer');
                        } else {
                            // Handle the case where trainer details are not found
                            $data['error'] = 'Trainer details not found.';
                            $this->view('home/home-login', $data);
                        }
                    } 
                     
                    elseif ($rolePrefix === 'MR') {
                        // Fetch additional member details
                        $manager = new M_Manager;
                        $managerDetails = $manager->findByManagerId($userDetails->user_id);

                        if ($managerDetails) {
                            // Store the member details in session
                            $_SESSION['role'] = 'manager';
                            $_SESSION['first_name'] = $managerDetails->first_name;
                            $_SESSION['last_name'] = $managerDetails->last_name;

                            // Redirect to member dashboard
                            redirect('manager');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Manager details not found.';
                            $this->view('home/home-login', $data);
                        }
                    }

                    elseif ($rolePrefix === 'RT') {
                        // Fetch additional member details
                        $receptionist = new M_Receptionist;
                        $receptionistDetails = $receptionist->findByReceptionistId($userDetails->user_id);

                        if ($receptionistDetails) {
                            // Store the member details in session
                            $_SESSION['role'] = 'receptionist';
                            $_SESSION['first_name'] = $receptionistDetails->first_name;
                            $_SESSION['last_name'] = $receptionistDetails->last_name;

                            // Redirect to member dashboard
                            redirect('receptionist');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Receptionist details not found.';
                            $this->view('home/home-login', $data);
                        }
                    }

                    elseif ($rolePrefix === 'AD') {
                        // Fetch additional member details
                        $admin = new M_Admin;
                        $adminDetails = $admin->findByAdminId($userDetails->user_id);

                        if ($adminDetails) {
                            // Store the member details in session
                            $_SESSION['role'] = 'admin';
                            $_SESSION['first_name'] = $adminDetails->first_name;
                            $_SESSION['last_name'] = $adminDetails->last_name;

                            // Redirect to member dashboard
                            redirect('admin');
                        } else {
                            // Handle the case where member details are not found
                            $data['error'] = 'Admin details not found.';
                            $this->view('home/home-login', $data);
                        }
                    }
                    else {
                        // Handle invalid user role prefix
                        $data['error'] = 'Invalid user role.';
                        $this->view('home/home-login', $data);
                    }
                } 
                else {
                    // Handle invalid password
                    $data['error'] = 'Invalid username or password.';
                    $this->view('home/home-login', $data);
                }
            } else {
                // Handle user not found
                $data['error'] = 'Invalid username or password.';
                $this->view('home/home-login', $data);
            }
        } 
        else {
            // Redirect to the login page if the request is not POST
            redirect('login');
        }
    }

    public function logout() {
        // Destroy the session
        session_destroy();
        // Redirect to login page with a success message
        $_SESSION['message'] = 'You have been logged out successfully.';
        redirect('login');
    }
}
