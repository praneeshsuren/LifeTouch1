<?php
class Service extends Controller
{
    public function __construct()
    {

    }
    public function index()
    {
        $this->view('manager/manager_dashboard');
    }

    public function createService()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $service = new M_Service;

            $postData = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if ($service->validate($postData)) {
                if ($service->insert($postData)) {
                    $_SESSION['success'] = "Service has been successfully added!";
                    redirect('manager/equipment_view/' . $postData['equipment_id']);
                } else {
                    $_SESSION['error'] = "There was an issue adding the service. Please try again.";
                    $_SESSION['form_errors'] = $service->errors;
                    redirect('manager/equipment_view/' . $postData['equipment_id']);
                }
            } else {
                $_SESSION['form_errors'] = $service->errors;
                $_SESSION['form_data'] = $postData;
                redirect('manager/equipment_view/' . $postData['equipment_id']);
            }
        } else {
            redirect('manager/equipment');
        }
    }

    public function updateService($id)
    {
        $serviceModel = new M_Service();

        $service = $serviceModel->where(['service_id' => $id], [], 'service_id');

        if (empty($service)) {
            $_SESSION['error'] = "Service not found";
            redirect('manager/equipment');
            return;
        }

        $equipment_id = $service[0]->equipment_id;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $updatedData = [
                'service_date' => $_POST['service_date'],
                'next_service_date' => $_POST['next_service_date'],
                'service_cost' => $_POST['service_cost'],
                'equipment_id' => $equipment_id,
            ];

            if ($serviceModel->validate($updatedData)) {
                if ($serviceModel->update($id, $updatedData, 'service_id')) {
                    $_SESSION['success'] = "Service updated successfully";
                    redirect('manager/equipment_view/' . $equipment_id);
                } else {
                    $_SESSION['error'] = "Failed to update service";
                    redirect('manager/equipment_view/' . $equipment_id);
                }
            } else {
                $_SESSION['form_errors'] = $serviceModel->errors;
                $_SESSION['form_data'] = $updatedData;
                redirect('service/updateService/' . $id);
            }
            return;
        }

        $form_data = $_SESSION['form_data'] ?? null;
        $form_errors = $_SESSION['form_errors'] ?? [];

        unset($_SESSION['form_data'], $_SESSION['form_errors']);

        $this->view('manager/service_edit', [
            'service' => (object)($form_data ?? (array)$service[0]),
            'equipment_id' => $equipment_id,
            'errors' => $form_errors
        ]);
    }

    public function deleteService($id)
    {
        $serviceModel = new M_Service(); 

        $result = $serviceModel->delete($id, 'service_id');  

        if ($result === false) {
            $_SESSION['message'] = 'Failed to delete service.';
        } else {
            $_SESSION['message'] = 'Service deleted successfully.';
        }

        $referer = $_SERVER['HTTP_REFERER'];
        header("Location: $referer");
        exit;
    }

}
?>