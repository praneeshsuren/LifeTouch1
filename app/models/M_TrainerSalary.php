<?php
    class M_TrainerSalary {
        
        use Model;

        protected $table = 'trainer_salary';
        protected $allowedColumns = [
            'id',
            'trainer_id',
            'salary',
            'bonus',
            'payment_date',
            'created_at'
        ];

        public function getSalaryHistoryByTrainerId($trainerId) {
            $query = "SELECT * FROM $this->table WHERE trainer_id = :trainer_id ORDER BY payment_date DESC";

            $params = [
                'trainer_id' => $trainerId
            ];

            return $this->query($query, $params);   
        }


    }
?>