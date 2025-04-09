<?php
class Service extends Controller
{
    public function __construct()
    {
        // Check if the user is logged in as a manager
        $this->checkAuth('manager');
    }
    public function index()
    {
        $this->view('manager/manager_dashboard');
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

    public function updateService($id)
    {
        $serviceModel = new M_Service();

        // Fetch the existing service details by ID
        
        $service = $serviceModel->where(['service_id' => $id], [], 1);

        if (!$service) {
            // If no service found, redirect to the equipment list
            redirect('manager/equipment');
        }

        // Process form submission when the request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect the form data
            $updatedData = [
                'service_date' => $_POST['date'],
                'next_service_date' => $_POST['next_date'],
                'service_cost' => $_POST['cost'],
            ];

            // Update the service in the database
            $updateResult = $serviceModel->update($id, $updatedData, 'service_id');

            if ($updateResult === false) {
                $_SESSION['message'] = "Failed to update service.";
            } else {
                $_SESSION['message'] = "Service updated successfully.";
            }

            // Redirect to the equipment list page after updating
            redirect('manager/equipment');
        }

        // Pass the service data to the view for editing
        $this->view('manager/service_edit', ['service' => $service]);
    }

public function deleteService($id)
{
    $serviceModel = new M_Service();  // Create an instance of the M_Equipment model

    // Call the delete method from the model
    $result = $serviceModel->delete($id, 'service_id');  // 'service_id' is the column to identify the service

    if ($result === false) {
        // Handle failure (e.g., redirect to the equipment list with a failure message)
        $_SESSION['message'] = 'Failed to delete service.';
    } else {
        // Handle success (e.g., redirect to the equipment list with a success message)
        $_SESSION['message'] = 'Service deleted successfully.';
    }

    // Redirect back to the same page
    $referer = $_SERVER['HTTP_REFERER'];
    header("Location: $referer");
    exit;
}

}
