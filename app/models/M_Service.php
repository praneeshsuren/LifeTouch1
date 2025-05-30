<?php

// Service class
class M_Service
{

    use Model;

    protected $table = 'service';
    protected $allowedColumns = [
        'service_id',
        'equipment_id', // Only the column name goes here
        'service_date',
        'next_service_date',
        'service_cost',
        'created_time'
    ];

    public function getMonthlyServicePaymentSum($startDate = null, $endDate = null)
    {
        // If no dates provided, default to the current month
        $startDate = $startDate ? $startDate : date('Y-m-01');
        $endDate = $endDate ? $endDate : date('Y-m-t');

        $query = "SELECT SUM(service_cost) as total 
              FROM {$this->table} 
              WHERE service_date BETWEEN :startDate AND :endDate";

        $params = [
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        $result = $this->query($query, $params);

        if (!empty($result)) {
            return $result[0]->total ?? 0;
        }
        return 0;
    }


    public function getOverdueServices()
    {
        $sql = "SELECT s.*, e.name AS equipment_name
            FROM service s
            LEFT JOIN equipment e ON s.equipment_id = e.equipment_id
            WHERE s.next_service_date <= CURDATE()";

        return $this->query($sql);
    }

    public function getUpcomingServices()
    {
        date_default_timezone_set('Asia/Colombo');

        $sql = "SELECT s.*, e.name AS equipment_name
            FROM service s
            LEFT JOIN equipment e ON s.equipment_id = e.equipment_id
            WHERE s.next_service_date > CURDATE()";

        return $this->query($sql);
    }


    public function findAll()
    {
        $sql = "SELECT s.*, e.name AS equipment_name 
                FROM service AS s  
                LEFT JOIN equipment AS e ON s.equipment_id = e.equipment_id";

        return $this->query($sql);
    }

    public function validate($data)
    {

        $this->errors = [];

        // Validate equipment_id
        if (empty($data['equipment_id'])) {
            $this->errors['equipment_id'] = "Equipment ID is required";
        }

        // Validate service_date
        if (empty($data['service_date'])) {
            $this->errors['service_date'] = "Date is required";
        }

        // Validate service_cost
        if (empty($data['service_cost'])) {
            $this->errors['service_cost'] = "Service cost is required";
        } elseif (!is_numeric($data['service_cost']) || $data['service_cost'] <= 0) {
            $this->errors['service_cost'] = "Service cost must be a valid number";
        }

        if (empty($data['next_service_date'])) {
            $this->errors['next_service_date'] = "Next service date is required";
        } elseif (strtotime($data['next_service_date']) <= strtotime($data['service_date'])) {
            $this->errors['next_service_date'] = "Next service date must be after the service date";
        } elseif (strtotime($data['next_service_date']) <= strtotime(date('Y-m-d'))) {
            $this->errors['next_service_date'] = "Next service date must be future date";
        }

        return empty($this->errors);
    }
    public function getErrors()
    {
        return $this->errors;
    }

    public function findByEquipmentId($equipment_id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE equipment_id = :equipment_id";
        $queryResult = $this->query($sql, ['equipment_id' => $equipment_id]);
    }
}
