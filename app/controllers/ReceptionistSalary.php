<?php

    class ReceptionistSalary extends Controller{

        public function addReceptionistSalaryPayment(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize and validate inputs
                $receptionistId = $_POST['receptionistId'];
                $paymentDate = $_POST['paymentDate'];
                $salary = $_POST['salary'];
                $bonus = $_POST['bonus'];
        
                 // Validate required fields
                if (empty($paymentDate) || empty($salary)) {
                    echo json_encode(['success' => false, 'message' => 'Payment date and salary are required.']);
                    return;
                }

                // Check if salary and bonus are numeric values
                if (!is_numeric($salary) || !is_numeric($bonus)) {
                    echo json_encode(['success' => false, 'message' => 'Salary and bonus must be numeric values.']);
                    return;
                }

                // Ensure bonus is treated as 0 if it is empty
                if (empty($bonus)) {
                    $bonus = 0;
                }

        
                // Insert the salary payment into the database
                $receptionistSalaryModel = new M_ReceptionistSalary();
                $data = [
                    'receptionist_id' => $receptionistId,
                    'salary' => $salary,
                    'bonus' => $bonus,
                    'payment_date' => $paymentDate,
                    'created_at' => date('Y-m-d H:i:s')
                ];
        
                $result = $receptionistSalaryModel->insert($data);
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add salary.']);
                }
            }
        }

        public function salaryHistory() {
            $receptionistId = $_GET['id'];
        
            // Retrieve the salary history for the given receptionist
            $receptionistSalaryModel = new M_ReceptionistSalary();
            $salaryHistory = $receptionistSalaryModel->getSalaryHistoryByReceptionistId($receptionistId);
        
            echo json_encode($salaryHistory);
        }

        public function deleteReceptionistSalaryPayment() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize input
                $salaryId = $_POST['id'];
        
                if (empty($salaryId)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid salary ID.']);
                    return;
                }
        
                // Delete the salary payment from the database
                $receptionistSalaryModel = new M_ReceptionistSalary();
                $result = $receptionistSalaryModel->delete($salaryId);
        
                if (!$result) {
                    echo json_encode(['success' => true, 'message' => 'Salary payment deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete salary payment.']);
                }
            }
        }

    }

?>