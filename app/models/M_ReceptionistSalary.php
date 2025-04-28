<?php
class M_ReceptionistSalary
{

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

    public function getReceptionistSalarySum($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            $query = "SELECT SUM(salary + bonus) as total FROM {$this->table} WHERE payment_date BETWEEN :startDate AND :endDate";
            $params = ['startDate' => $startDate, 'endDate' => $endDate];
        } else {
            $currentMonth = date('Y-m');
            $query = "SELECT SUM(salary + bonus) as total FROM {$this->table} WHERE DATE_FORMAT(payment_date, '%Y-%m') = :currentMonth";
            $params = ['currentMonth' => $currentMonth];
        }

        $result = $this->query($query, $params);
        if (!empty($result)) {
            return $result[0]->total ?? 0;
        }
        return 0;
    }

    public function getSalaryHistoryByReceptionistId($receptionistId)
    {
        $query = "SELECT * FROM $this->table WHERE receptionist_id = :receptionist_id ORDER BY payment_date DESC";

        $params = [
            'receptionist_id' => $receptionistId
        ];

        return $this->query($query, $params);
    }
}
