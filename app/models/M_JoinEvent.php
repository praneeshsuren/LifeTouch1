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
        'email'
    ];

    public function getEventdetails()
    {
        $query = "SELECT * FROM event";
        return $this->query($query);
    }
    public function getEventParticipantSummary()
    {
        $query = "
    SELECT 
        e.name,
        e.event_id,
        COUNT(ep.id) AS participant_count,

        SUM(
            CASE
                WHEN e.free_for_members = 1 AND ep.is_member = 1 THEN 0
                ELSE 1
            END
        ) AS paid_count,

        SUM(
            CASE
                WHEN e.free_for_members = 1 AND ep.is_member = 1 THEN 0
                ELSE e.price
            END
        ) AS total_revenue

    FROM event e
    LEFT JOIN event_participants ep ON e.event_id = ep.event_id
    GROUP BY e.event_id, e.name, e.price, e.free_for_members
    ";

        return $this->query($query);
    }

    // Validate input data
    public function validate($data)
    {
        $this->errors = []; // Reset errors for every validation call

        // Name validation
        if (empty($data['full_name'])) {
            $this->errors['full_name'] = "Name is required.";
        }

        // Validate NIC (Sri Lankan National Identity Card)
        $nic = strtoupper(trim($data['nic'])); // Normalize to uppercase

        if (empty($nic)) {
            $this->errors['nic'] = "NIC is required.";
        } elseif (!preg_match('/^(?:\d{9}[V]|\d{12})$/', $nic)) {
            $this->errors['nic'] = "Invalid NIC format. Must be either:
        - 9 digits ending with V  (e.g., 123456789V)
        - 12 digits (e.g., 200123456789)";
        }



        // Date validation
        if (!empty($data['is_member']) && empty($data['membership_number'])) {
            $this->errors['membership_number'] = "Membership number is required for gym members.";
        } elseif (!empty($data['membership_number'])) {
            // Check if membership number exists in member table
            $memberExists = $this->query("SELECT * FROM member WHERE member_id = :membership_number", [
                'membership_number' => $data['membership_number']
            ]);
        
            if (empty($memberExists)) {
                $this->errors['membership_number'] = "Invalid membership number. Please provide a valid one.";
            } else {
                //  Check for duplicates in the event
                $alreadyJoined = $this->query("SELECT * FROM event_participants WHERE event_id = :event_id AND membership_number = :membership_number", [
                    'event_id' => $data['event_id'],
                    'membership_number' => $data['membership_number']
                ]);
        
                if (!empty($alreadyJoined)) {
                    $this->errors['membership_number'] = "This gym member is already registered for this event.";
                }
            }
        } else {
            if (empty($data['is_member']) && !empty($data['membership_number'])) {
                $this->errors['membership_number'] = "Only gym members should provide a membership number.";
            }
        }
        
        if (empty($data['email'])) {
            $this->errors['email'] = "Email is required.";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Please enter a valid email address.";
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
