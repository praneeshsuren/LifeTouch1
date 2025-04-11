<?php

// Equipment class
class M_JoinEvent
{
    use Model;

    protected $table = 'event_participants';

    // List of allowed columns for insertion or updates
    protected $allowedColumns = [
        'id',
        'event_id',
        'full_name',
        'nic',
        'is_member',
        'membership_number',
        'contact_no'
    ];


    // Validate input data
    public function validate($data)
    {
        $this->errors = []; // Reset errors for every validation call

        // Name validation
        if (empty($data['full_name'])) {
            $this->errors['full_name'] = "Name is required.";
        }

        // Description validation
        if (empty($data['nic'])) {
            $this->errors['nic'] = "Nic content is required.";
        }

        // Date validation
        if (!empty($data['is_member']) && empty($data['membership_number'])) {
            $this->errors['membership_number'] = "Membership number is required for gym members.";
        } elseif (!empty($data['membership_number'])) {
            $memberExists = $this->query("SELECT * FROM member WHERE member_id = :membership_number", [
            'membership_number' => $data['membership_number']
            ]);
            if (empty($memberExists)) {
            $this->errors['membership_number'] = "Invalid membership number. Please provide a valid one.";
            }
        }
        else{
        if (empty($data['is_member']) && empty($data['membership_number'])) {
            $this->errors['membership_number'] = "Only gym members should provide a membership number.";
        }
    }
        if (empty($data['contact_no'])) {
            $this->errors['contact_no'] = "Contact no is required.";
        }

        // Return true if no errors; otherwise, false
        return empty($this->errors);
    }


    // Method to get errors after validation
    public function getErrors()
    {
        return $this->errors;
    }
}
