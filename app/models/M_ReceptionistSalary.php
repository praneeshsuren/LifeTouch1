<?php
    class M_ReceptionistSalary {
        
        use Model;

        protected $table = 'receptionist_salary';
        protected $allowedColumns = [
            'id',
            'receptionist_id',
            'salary',
            'bonus',
            'payment_date',
            'created_at'
        ];

        public function getSalaryHistoryByReceptionistId($receptionistId) {
            $query = "SELECT * FROM $this->table WHERE receptionist_id = :receptionist_id ORDER BY payment_date DESC";

            $params = [
                'receptionist_id' => $receptionistId
            ];

            return $this->query($query, $params);   
        }


    }
?>