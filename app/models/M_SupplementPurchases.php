<?php

class M_SupplementPurchases
{

    use Model;

    protected $table = 'supplement_purchases';

    // List of allowed columns for insertion or updates
    protected $allowedColumns = [
        's_purchaseId',
        'supplement_id',
        'purchase_date',
        'purchase_price',
        'quantity',
        'purchase_shop'
    ];

    public function getMonthlySupplementPurchase()
    {
        $currentMonth = date('Y-m');
        $query = "SELECT SUM(purchase_price) as total FROM {$this->table} WHERE DATE_FORMAT(purchase_date, '%Y-%m') = :currentMonth";
        $params = ['currentMonth' => $currentMonth];

        $result = $this->query($query, $params);
        if (!empty($result)) {
            return $result[0]->total ?? 0;
        }
        return 0;
    }

    public function getSupplementId($purchase_id)
    {
        $query = "SELECT supplement_id FROM $this->table WHERE s_purchaseID = $purchase_id";

        // Execute the query to fetch the last inserted schedule_id
        $result = $this->get_row($query);
        // Return the retrieved schedule_id
        return $result ? $result->supplement_id : null;
    }

    public function getPurchase($purchase_id)
    {
        $query = "SELECT * FROM $this->table WHERE s_purchaseID = $purchase_id";

        // Execute the query to fetch the last inserted schedule_id
        $result = $this->get_row($query);
        // Return the retrieved schedule_id
        return $result ? $result : null;
    }
}
