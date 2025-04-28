<?php

// Equipment class
class M_Equipment
{
    use Model;

    protected $table = 'equipment';

    // List of allowed columns for insertion or updates
    protected $allowedColumns = [
        'equipment_id',
        'name',
        'description',
        'file',
        'purchase_price',
        'purchase_date',
        'purchase_shop',
    ];

    public function getEquipmentPurchaseSum($startDate = null, $endDate = null)
{
    if ($startDate && $endDate) {
        $query = "SELECT SUM(purchase_price) as total FROM {$this->table} WHERE purchase_date BETWEEN :startDate AND :endDate";
        $params = ['startDate' => $startDate, 'endDate' => $endDate];
    } elseif ($startDate) {
        $query = "SELECT SUM(purchase_price) as total FROM {$this->table} WHERE purchase_date >= :startDate AND purchase_date <= CURDATE()";
        $params = ['startDate' => $startDate];
    } elseif ($endDate) {
        $query = "SELECT SUM(purchase_price) as total FROM {$this->table} WHERE purchase_date <= :endDate";
        $params = ['endDate' => $endDate];
    } else {
        $currentMonth = date('Y-m');
        $query = "SELECT SUM(purchase_price) as total FROM {$this->table} WHERE DATE_FORMAT(purchase_date, '%Y-%m') = :currentMonth";
        $params = ['currentMonth' => $currentMonth];
    }

    $result = $this->query($query, $params);
    if (!empty($result)) {
        return $result[0]->total ?? 0;
    }
    return 0;
}

    


    // Validate input data
    public function validate($data)
    {
        $this->errors = []; // Reset errors for every validation call

        // Name validation
        if (empty($data['name'])) {
            $this->errors['name'] = "Name is required.";
        }

        // Description validation
        if (empty($data['description'])) {
            $this->errors['description'] = "Equipment content is required.";
        }

        // File validation
        if (!isset($_FILES['image']) || empty($_FILES['image']['name'])) {
            $this->errors['file'] = "Image file is required.";
        } else {
            if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
                $this->errors['file'] = "File upload failed. Please try again.";
            } elseif ($_FILES['image']['size'] > 2 * 1024 * 1024) { // Check if file size exceeds 2MB
                $this->errors['file'] = "The file size must be less than 2MB.";
            }
        }
        // Date validation
        if (empty($data['purchase_date'])) {
            $this->errors['purchase_date'] = "Purchase date is required.";
        }
        if (empty($data['purchase_shop'])) {
            $this->errors['purchase_shop'] = "Purchase shop is required.";
        }

        // Price validation
        if (empty($data['purchase_price'])) {
            $this->errors['purchase_price'] = "Price is required.";
        } elseif (!is_numeric($data['purchase_price']) || $data['purchase_price'] <= 0) { // Ensure price is numeric and positive
            $this->errors['purchase_price'] = "Price must be a positive number.";
        }

        // Return true if no errors; otherwise, false
        return empty($this->errors);
    }

    // Method to get errors after validation
    public function getErrors()
    {
        return $this->errors;
    }
    public function countAllEquipment()
    {
        // SQL query to count all equipment
        $sql = "SELECT COUNT(*) as total FROM $this->table";

        // Execute the query and fetch the result
        $result = $this->get_row($sql);

        // Return the total count
        return $result ? $result->total : 0;
    }

    public function getSuggestionsByName($query) {
        $query = "%" . strtolower($query) . "%";

        $sql = "SELECT name, equipment_id, file FROM $this->table WHERE LOWER(name) LIKE :query"; 
        
        $data = ['query' => $query];
        return $this->query($sql, $data);
    }
}
