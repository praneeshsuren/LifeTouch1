<?php

    class TrainerSalary extends Controller{

        public function addTrainerSalaryPayment(){
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize and validate inputs
                $trainerId = $_POST['trainerId'];
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
                $trainerSalaryModel = new M_TrainerSalary();
                $data = [
                    'trainer_id' => $trainerId,
                    'salary' => $salary,
                    'bonus' => $bonus,
                    'payment_date' => $paymentDate,
                    'created_at' => date('Y-m-d H:i:s')
                ];
        
                $result = $trainerSalaryModel->insert($data);
                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add salary.']);
                }
            }
        }

        public function salaryHistory() {
            $trainerId = $_GET['id'];
        
            // Retrieve the salary history for the given trainer
            $trainerSalaryModel = new M_TrainerSalary();
            $salaryHistory = $trainerSalaryModel->getSalaryHistoryByTrainerId($trainerId);
        
            echo json_encode($salaryHistory);
        }

        public function deleteTrainerSalaryPayment() {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Sanitize input
                $salaryId = $_POST['id'];
        
                if (empty($salaryId)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid salary ID.']);
                    return;
                }
        
                // Delete the salary payment from the database
                $trainerSalaryModel = new M_TrainerSalary();
                $result = $trainerSalaryModel->delete($salaryId);
        
                if (!$result) {
                    echo json_encode(['success' => true, 'message' => 'Salary payment deleted successfully.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete salary payment.']);
                }
            }
        }

    }

?>