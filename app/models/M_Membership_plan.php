<?php

// Service class
class M_Membership_plan
{

    use Model;

    protected $table = 'membership_plan';
    protected $allowedColumns = [
        'membershipPlan_id',
        'plan',
        'amount',
    ];

    public function validate($data)
    {

        $this->errors = [];

        // Validate service_date
        if (empty($data['plan'])) {
            $this->errors['plan'] = "Plan is required";
        }

        // Validate service_cost
        if (empty($data['amount'])) {
            $this->errors['amount'] = "Amount is required.";
        } elseif (!is_numeric($data['amount']) || $data['amount'] <= 0) { // Ensure price is numeric and positive
            $this->errors['amount'] = "Amount must be a positive number.";
        }

        return empty($this->errors);
    }
    public function getErrors()
    {
        return $this->errors;
    }
}
