<?php
class M_TrainerSalary
{

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

    public function getTrainerSalarySum($startDate = null, $endDate = null)
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

    public function getSalaryHistoryByTrainerId($trainerId)
    {
        $query = "SELECT * FROM $this->table WHERE trainer_id = :trainer_id ORDER BY payment_date DESC";

        $params = [
            'trainer_id' => $trainerId
        ];

        return $this->query($query, $params);
    }
}
