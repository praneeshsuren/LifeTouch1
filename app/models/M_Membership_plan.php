<?php

//Admin class
class M_Membership_plan
{

    use Model;

    protected $table = 'membership_plan';
    protected $allowedColumns = [
        'membershipPlan_id',
        'plan',
        'duration',
        'amount'
    ];

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['plan'])) {
            $this->errors['plan'] = 'Plan name is required';
        }
        if (empty($data['duration'])) {
            $this->errors['duration'] = 'Duration is required';
        }
        if (empty($data['amount'])) {
            $this->errors['amount'] = 'Amount is required';
        }
        // Add validation for numeric values if needed
        if (!empty($data['amount']) && !is_numeric($data['amount'])) {
            $this->errors['amount'] = 'Amount must be a number';
        }
        if (!empty($data['duration']) && !is_numeric($data['duration'])) {
            $this->errors['duration'] = 'Duration must be a number';
        }

        return empty($this->errors);
    }



    // Method to get errors after validation
    public function getErrors()
    {
        return $this->errors;
    }
}
