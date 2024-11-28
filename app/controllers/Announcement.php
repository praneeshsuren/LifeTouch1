<?php
    class Announcement extends Controller{

        public function createAnnouncement(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $announcement = new M_Announcement;

                if ($announcement->validate($_POST)){
                    $temp = $_POST;

                    $temp['announcement_id'] = 'A';
                    $offset = str_pad($announcement->countAll() + 1, 4, '0', STR_PAD_LEFT);
                    $temp['announcement_id'] .= $offset;

                    $temp['created_by'] = $_SESSION['user_id'];
                    
                    $announcement->insert($temp);
                    $_SESSION['success'] = "Announcement has been successfully published!";

                    redirect('admin/announcements');
                }
                else{
                    $data['errors'] = $announcement->errors;
                    $this->view('admin/admin-createAnnouncement', $data);
                }
            }
            else{
                redirect('admin/announcements');
            }
        }

        public function updateAnnouncement(){
            if($_SERVER['REQUEST_METHOD'] == 'POST'){

                $announcementModel = new M_Announcement;

                if($announcementModel->validate($_POST)){

                    $data = [
                        'subject'    => $_POST['subject'],
                        'description'     => $_POST['description']
                    ];

                    $announcement_id = $_POST['announcement_id'];

                    // Call the update function
                    if (!$announcementModel->update($announcement_id, $data, 'announcement_id')) {
                        // Set a success session message
                        $_SESSION['success'] = "Announcement has been successfully updated!";
                        // Redirect to the announcements view page
                        redirect('admin/announcements');
                    } else {
                        // Handle update failure (optional)
                        $_SESSION['error'] = "There was an issue updating the announcement. Please try again.";
                        redirect('admin/announcements');
                    }
                }
                else {
                    // If validation fails, pass errors to the view
                    $data = [
                        'errors' => $announcementModel->errors,
                        'trainer' => $_POST // Preserve form data for user correction
                    ];
                    // Render the view with errors and form data
                    $this->view('admin/announcements', $data);
                }
            } else {
                // Redirect if the request is not a POST request
                redirect('admin/announcements');
            }

        }

        public function deleteAnnouncement(){

            $announcementModel = new M_Announcement;

            $announcement_id = $_POST['announcement_id'];

            if (!$announcementModel->delete($announcement_id, 'announcement_id')) {
                
                $_SESSION['success'] = "Announcement has been deleted successfully";

                redirect('admin/announcements');
            } 
            else {
                // Handle deletion failure
                $_SESSION['error'] = "There was an issue deleting the announcement. Please try again.";
                redirect('admin/announcements');
            }
        }

    }