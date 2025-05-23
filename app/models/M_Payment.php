<?php

//Admin class
class M_Payment
{

    use Model;

    protected $table = 'payment';
    protected $allowedColumns = [
        'id',
        'member_id',
        'plan_id',
        'payment_intent_id',
        'status',
        'type',
        'start_date',
        'end_date',
    ];

    public function getTotalPayment($startDate = null, $endDate = null)
    {
        // Use provided dates or default to current month if not provided
        $startDate = $startDate ? $startDate : date('Y-m-01'); // Default to the first day of the current month
        $endDate = $endDate ? $endDate : date('Y-m-t'); // Default to the last day of the current month

        // Sum online payments
        $query1 = "SELECT SUM(mp.amount) as total
               FROM payment p
               JOIN membership_plan mp ON p.plan_id = mp.membershipPlan_id
               WHERE p.status = 'succeeded' 
                 AND p.start_date BETWEEN :startDate AND :endDate";

        // Sum physical payments
        $query2 = "SELECT SUM(mp.amount) as total
               FROM physical_payment pp
               JOIN membership_plan mp ON pp.plan_id = mp.membershipPlan_id
               WHERE pp.start_date BETWEEN :startDate AND :endDate";

        $params = ['startDate' => $startDate, 'endDate' => $endDate];

        // Execute the queries
        $online = $this->query($query1, $params);
        $physical = $this->query($query2, $params);

        // If no results are returned, set totals to 0
        $onlineTotal = !empty($online) ? ($online[0]->total ?? 0) : 0;
        $physicalTotal = !empty($physical) ? ($physical[0]->total ?? 0) : 0;

        // Return the sum of both online and physical payments
        return $onlineTotal + $physicalTotal;
    }

    public function paymentMember($member_id)
    {
        $query = "Select p.* from $this->table AS p
            WHERE p.member_id = :member_id";

        return $this->query($query, ['member_id' => $member_id]);
    }

    public function paymentAdmin()
    {
        $query = "SELECT
                p.*,
                m.member_id AS member_id, 
                CONCAT(m.first_name, ' ', m.last_name) AS member_name
             FROM payment AS p
             JOIN member m ON p.member_id = m.member_id";

        return $this->query($query);
    }

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['plan_id'])) {
            $this->errors['plan_id'] = 'plan_id is required';
        }
        if (empty($data['type'])) {
            $this->errors['type'] = 'type is required';
        }
        if (empty($data['start_date'])) {
            $this->errors['start_date'] = 'start_date is required';
        }
        if (empty($data['end_date'])) {
            $this->errors['end_date'] = 'end_date is required';
        }
        if (empty($data['member_id'])) {
            $this->errors['member_id'] = 'member_id is required';
        }
        if (empty($data['status'])) {
            $this->errors['status'] = 'status is required';
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
