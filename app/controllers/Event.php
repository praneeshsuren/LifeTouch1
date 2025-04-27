<?php

    class Event extends Controller{

        public function createEvent(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $eventModel = new M_Event;

                if ($eventModel->validate($_POST)){
                    $temp = $_POST;
                    
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

        public function updateEvent()
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $eventModel = new M_Event;
                $event_id = $_POST['event_id'];
                // First, fetch the existing event details from the database
                $existingEvent = $eventModel->findByEventId($event_id);

                if (!$existingEvent) {
                    $_SESSION['error'] = "Event not found.";
                    redirect('admin/events');
                }

                $updatedFields = [];
                $fieldsToValidate = [];

                // List of fields you want to check
                $fields = ['name', 'description', 'start_time', 'duration', 'location', 'event_date', 'price', 'status'];

                foreach ($fields as $field) {
                    if (isset($_POST[$field])) {
                        $existingValue = property_exists($existingEvent, $field) ? $existingEvent->$field : null;
                        
                        if ($_POST[$field] != $existingValue) {
                            $updatedFields[$field] = $_POST[$field];
                            $fieldsToValidate[$field] = $_POST[$field];
                        }
                    }
                }                

                if (empty($updatedFields)) {
                    $_SESSION['success'] = "No changes detected.";
                    redirect('admin/events/viewEvent?id=' . $_POST['event_id']);
                }

                // Validate only changed fields
                if ($eventModel->validate($fieldsToValidate)) {
                    $event_id = $_POST['event_id'];
                    $update = $eventModel->update($event_id, $updatedFields, 'event_id');
                    if (!$update) {
                        $_SESSION['success'] = "Event has been successfully updated!";
                        redirect('admin/events/viewEvent?id=' . $event_id);
                    } else {
                        $_SESSION['error'] = "There was an issue updating the event. Please try again.";
                        redirect('admin/events/viewEvent?id=' . $event_id);
                    }
                } else {
                    // If validation fails
                    $data = [
                        'errors' => $eventModel->errors,
                        'event' => $_POST
                    ];
                    $this->view('admin/admin-viewEvent', $data);
                }
            } else {
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