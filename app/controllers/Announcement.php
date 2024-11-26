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

    }