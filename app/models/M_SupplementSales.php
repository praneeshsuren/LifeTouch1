<?php

class M_SupplementSales
{

    use Model;

    protected $table = 'supplement_sales';

    // List of allowed columns for insertion or updates
    protected $allowedColumns = [
        'sale_id',
        'supplement_id',
        'member_id',
        'quantity',
        'price_of_a_supplement',
        'sale_date',
    ];

    public function getSupplementSales($startDate = null, $endDate = null)
{
    // If no dates are provided, default to the current month
    $startDate = $startDate ? $startDate : date('Y-m-01');
    $endDate = $endDate ? $endDate : date('Y-m-t');

    $query = "SELECT SUM(price_of_a_supplement) as total 
              FROM {$this->table} 
              WHERE sale_date BETWEEN :startDate AND :endDate";

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

    public function findByMemberId($member_id)
    {
        $query = "SELECT ss.*, s.name, s.file
                    FROM $this->table ss
                    JOIN supplement s ON ss.supplement_id = s.supplement_id
                    WHERE ss.member_id = '$member_id'
                    ORDER BY ss.sale_date DESC";

        // Prepare and execute the query
        $result = $this->query($query);

        // Return the results or an empty array if no results found
        return $result ?: [];  // Returns an empty array if no supplements exist
    }

    public function findSupplementsPurchased($member_id)
    {
        $query = "SELECT *
                    FROM $this->table
                    WHERE member_id = :memberID";

        $result = $this->query($query, ['memberID' => $member_id]);
        // Return the results or an empty array if no results found
        return $result ?: [];
    }
}
