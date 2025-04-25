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


        // If there are no errors, return true; otherwise, return false.
        return empty($this->errors);
    }

    // Method to get errors after validation
    public function getErrors()
    {
        return $this->errors;
    }
}
