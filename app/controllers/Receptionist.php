<?php

    class Receptionist extends Controller{
        
        public function index(){
            $this->view('receptionist/receptionist-dashboard');
        }

        public function members(){
            $this->view('receptionist/receptionist-members');
        }

        public function trainers($action = null) {
            switch ($action) {
                case 'createTrainer':
                    $this->view('receptionist/receptionist-createTrainer'); // Adjust the view path as needed
                    break;
                    
                case 'registerTrainer':
                    $user = new M_Trainer;
                    if($user->validate($_POST)){
                        $user->insert($_POST);
                        redirect('receptionist/trainers/trainerCredentials');
                    }

                    $data['errors'] = $user->errors;
                    $this->view('receptionist/receptionist-createTrainer', $data);
                    break;

                case 'trainerCredentials':
                    $this->view('receptionist/receptionist-trainerCredentials');
                    break;

                default:
                    $trainerModel = new M_Trainer;
                    $trainers = $trainerModel->findAll();

                    $data = [
                        'trainers' => $trainers
                    ];

                    $this->view('receptionist/receptionist-trainers', $data);
                    break;
            }
        }
        

    }