<?php
class Service extends Controller
{
    public function __construct()
    {
        // Check if the user is logged in as a manager
        $this->checkAuth('manager');
    }

    public function createService()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $service = new M_Service;

            // Sanitize input
            $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if ($service->validate($postData)) {
                if ($service->insert($postData)) {
                    $_SESSION['success'] = "Service has been successfully added!";
                    
                    // Get the updated service history and pass it to the view
                    $data['services'] = $service->findAll(); 
                    redirect('manager/equipment_view/'.$postData['equipment_id']); // Pass the services data to the view
                } else {
                    $_SESSION['error'] = "There was an issue adding the service. Please try again.";
                    redirect('manager/equipment_view');
                }
            } else {
                $data['errors'] = $service->errors;
                $this->view('manager/equipment_view', $data);
            }
        } else {
            redirect('manager/equipment_view');
        }
    }

    public function viewServices()
    {
        $service = new M_Service();
        $data['services'] = $service->findAll();

        // Debugging: Print the fetched data
        echo '<pre>';
        print_r($data['services']);
        echo '</pre>';

        $this->view('manager/equipment_view', $data);
    }

    public function updateService()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $service = new M_Service;

            // Sanitize input
            $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if ($service->validate($postData)) {
                $service_id = $postData['service_id'];
                if ($service->update($service_id, $postData)) {
                    $_SESSION['success'] = "Service has been successfully updated!";
                    redirect('manager/equipment_view');
                } else {
                    $_SESSION['error'] = "There was an issue updating the service. Please try again.";
                    redirect('manager/equipment_view');
                }
            } else {
                $data['errors'] = $service->errors;
                $this->view('manager/equipment_view', $data);
            }
        } else {
            redirect('manager/equipment_view');
        }
    }

    public function deleteService()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $service = new M_Service;
            $service_id = $_POST['service_id'];

            if ($service->delete($service_id)) {
                $_SESSION['success'] = "Service has been successfully deleted!";
                redirect('manager/equipment_view');
            } else {
                $_SESSION['error'] = "There was an issue deleting the service. Please try again.";
                redirect('manager/equipment_view');
            }
        } else {
            redirect('manager/equipment_view');
        }
    }
}
