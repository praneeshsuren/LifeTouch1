<?php

    class AdminSalary extends Controller{

        public function addAdminSalaryPayment(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize and validate inputs
                $adminId = $_POST['adminId'];
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
                $adminSalaryModel = new M_AdminSalary();
                $data = [
                    'admin_id' => $adminId,
                    'salary' => $salary,
                    'bonus' => $bonus,
                    'payment_date' => $paymentDate,
                    'created_at' => date('Y-m-d H:i:s')
                ];
        
                $result = $adminSalaryModel->insert($data);
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add salary.']);
                }
            }
        }

        public function salaryHistory() {
            $adminId = $_GET['id'];
        
            // Retrieve the salary history for the given admin
            $adminSalaryModel = new M_AdminSalary();
            $salaryHistory = $adminSalaryModel->getSalaryHistoryByAdminId($adminId);
        
            echo json_encode($salaryHistory);
        }

        public function deleteAdminSalaryPayment() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize input
                $salaryId = $_POST['id'];
        
                if (empty($salaryId)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid salary ID.']);
                    return;
                }
        
                // Delete the salary payment from the database
                $adminSalaryModel = new M_AdminSalary();
                $result = $adminSalaryModel->delete($salaryId);
        
                if (!$result) {
                    echo json_encode(['success' => true, 'message' => 'Salary payment deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete salary payment.']);
                }
            }
        }

    }

?>