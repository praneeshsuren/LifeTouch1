<?php
    class M_AdminSalary {
        
        use Model;

        protected $table = 'admin_salary';
        protected $allowedColumns = [
            'id',
            'admin_id',
            'salary',
            'bonus',
            'payment_date',
            'created_at'
        ];

        public function getSalaryHistoryByAdminId($adminId) {
            $query = "SELECT * FROM $this->table WHERE admin_id = :admin_id ORDER BY payment_date DESC";

            $params = [
                'admin_id' => $adminId
            ];

            return $this->query($query, $params);   
        }


    }
?>