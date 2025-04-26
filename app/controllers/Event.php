<?php

    class Event extends Controller{

        public function createEvent(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $eventModel = new M_Event;

                if ($eventModel->validate($_POST)){
                    $temp = $_POST;

                    $temp['event_id'] = 'E';
                    $offset = str_pad($eventModel->countAll() + 1, 4, '0', STR_PAD_LEFT);
                    $temp['event_id'] .= $offset;
                    $temp['status'] = 'Ongoing';

                    $message = "A new event has been published: " . $temp['name'];
                    $userType = 'all'; // Send notification to all users

                    // Notify all users
                    $notificationModel = new M_Notification;
                    $notificationModel->notifyAllUsers($message, $userType);

                    $eventModel->insert($temp);
                    $_SESSION['success'] = "Event has been successfully published!";

                    redirect('admin/events');
                }
                else{
                    $data['errors'] = $eventModel->errors;
                    $this->view('admin/admin-createEvent', $data);
                }
            }
            else{
                redirect('admin/events');
            }
        }

        public function updateEvent(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $eventModel = new M_Event;

                if($eventModel->validate($_POST)){

                    $data = [
                        'name'            => $_POST['name'],
                        'description'     => $_POST['description'],
                        'start_time'      => $_POST['start_time'],
                        'duration'        => $_POST['duration'],
                        'location'        => $_POST['location'],
                        'event_date'      => $_POST['event_date'],
                        'price'           => $_POST['price'],
                        'status'          => $_POST['status']
                    ];

                    $event_id = $_POST['event_id'];

                    // Call the update function
                    if (!$eventModel->update($event_id, $data, 'event_id')) {
                        // Set a success session message
                        $_SESSION['success'] = "Event has been successfully updated!";
                        // Redirect to the events view page
                        redirect('admin/events/viewEvent?id=' .$event_id);
                    } else {
                        // Handle update failure (optional)
                        $_SESSION['error'] = "There was an issue updating the event. Please try again.";
                        redirect('admin/events/viewEvent?id=' .$event_id);
                    }
                }
                else {
                    // If validation fails, pass errors to the view
                    $data = [
                        'errors' => $eventModel->errors,
                        'event' => $_POST // Preserve form data for user correction
                    ];
                    // Render the view with errors and form data
                    $this->view('admin/admin-viewEvent', $data);
                }
            } else {
                // Redirect if the request is not a POST request
                redirect('admin/events');
            }

        }

        public function deleteEvent(){

            $eventModel = new M_event;

            $event_id = $_GET['id'];

            if (!$eventModel->delete($event_id, 'event_id')) {
                
                $_SESSION['success'] = "Event has been deleted successfully";

                redirect('admin/events');
            } 
            else {
                // Handle deletion failure
                $_SESSION['error'] = "There was an issue deleting the event. Please try again.";
                redirect('admin/events/viewEvent?id=' .$event_id);
            }
        }

    }