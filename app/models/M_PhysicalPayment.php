<?php

class M_PhysicalPayment
{
    use Model;

    protected $table = 'physical_payment';
    protected $allowedColumns = [
        'id',
        'member_id',
        'plan_id',
        'start_date',
        'end_date',
    ];

    public function getPaymentHistory($member_id)
    {
        // Correct online payment query
        $onlinePayments = $this->query(
            "SELECT p.*, mp.plan as plan_name, mp.amount, 'online' as payment_type
         FROM payment p
         JOIN membership_plan mp ON p.plan_id = mp.membershipPlan_id
         WHERE p.member_id = :member_id
         ORDER BY p.start_date DESC",
            [':member_id' => $member_id]
        );

        // Physical payments
        $physicalPayments = $this->query(
            "SELECT pp.*, mp.plan as plan_name, mp.amount, 'physical' as payment_type
         FROM physical_payment pp
         JOIN membership_plan mp ON pp.plan_id = mp.membershipPlan_id
         WHERE pp.member_id = :member_id
         ORDER BY pp.start_date DESC",
            [':member_id' => $member_id]
        );

        // Combine and sort
        $allPayments = array_merge($onlinePayments, $physicalPayments);
        usort($allPayments, function ($a, $b) {
            return strtotime($b->start_date) - strtotime($a->start_date);
        });

        return $allPayments;
    }


    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['plan_id'])) {
            $this->errors['plan_id'] = 'plan_id is required';
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

        // Now check if dates overlap with existing physical payments
        if (!empty($data['member_id']) && !empty($data['start_date']) && !empty($data['end_date'])) {
            $query = "SELECT * FROM $this->table 
              WHERE member_id = :member_id 
              AND (
                    (:start_date BETWEEN start_date AND end_date) OR
                    (:end_date BETWEEN start_date AND end_date) OR
                    (start_date BETWEEN :start_date AND :end_date)
                  )
              LIMIT 1";

            $params = [
                'member_id' => $data['member_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
            ];

            $result = $this->query($query, $params);

            if (!empty($result)) {
                $this->errors['start_date'] = 'This member already has a plan between '
                    . $result[0]->start_date . ' and ' . $result[0]->end_date;
            }
        }
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
