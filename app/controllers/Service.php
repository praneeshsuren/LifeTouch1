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

    // First handle form submission if POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $updatedData = [
            'service_date' => $_POST['service_date'],
            'next_service_date' => $_POST['next_service_date'],
            'service_cost' => $_POST['service_cost'],
        ];

        if ($serviceModel->update($id, $updatedData, 'service_id')) {
            $_SESSION['success'] = "Service updated successfully";
            
            // Get the equipment_id from the updated service
            $service = $serviceModel->where(['service_id' => $id], [], 'service_id');
            redirect('manager/equipment_view/'.$service[0]->equipment_id);
        } else {
            $_SESSION['error'] = "Failed to update service";
            redirect('manager/equipment');
        }
        return;
    }

    // For GET requests - fetch and display the service
    $service = $serviceModel->where(['service_id' => $id], [], 'service_id');

    // Debug output - remove after testing
    echo '<pre>Service Data: '; print_r($service); echo '</pre>';

    if (empty($service)) {
        $_SESSION['error'] = "Service not found";
        redirect('manager/equipment');
        return;
    }

    // Pass to view - ensure we're using the first result
    $this->view('manager/service_edit', [
        'service' => $service[0],  // Use the first item from the array
        'equipment_id' => $service[0]->equipment_id
    ]);
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
