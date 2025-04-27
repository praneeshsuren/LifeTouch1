<?php

//Announcement class
class M_Event
{

    use Model;

    protected $table = 'event';
    protected $allowedColumns = [
        'event_id',
        'name',
        'description',
        'start_time',
        'duration',
        'location',
        'event_date',
        'price',
        'status',
        'free_for_members',
        'created_at',
        'updated_at'
    ];
    
    public function getEventById($event_id)
    {
        $query = "SELECT * FROM event WHERE event_id = :event_id";
        $result = $this->query($query, ['event_id' => $event_id]);

        if (!empty($result)) {
            return (object) $result[0]; // 
        }

        return null;
    }

    
    public function findByEventId($eventId) {
        $data = ['event_id' => $eventId];
        return $this->first($data);  // Use the `first` method to get the first matching record
    }

    public function validate($data)
    {
        $this->errors = [];

        // Validate Event Name
        if (isset($data['name'])) {
            if (empty(trim($data['name']))) {
                $this->errors['name'] = "Event name is required.";
            }
        }

        // Validate Description
        if (isset($data['description'])) {
            if (empty(trim($data['description']))) {
                $this->errors['description'] = "Description is required.";
            }
        }

        // Validate Event Date
        if (isset($data['event_date'])) {
            if (empty($data['event_date'])) {
                $this->errors['event_date'] = "Event date is required.";
            } elseif (strtotime($data['event_date']) < strtotime(date('Y-m-d'))) {
                $this->errors['event_date'] = "Event date must be today or a future date.";
            }
        }

        // Validate Start Time
        if (isset($data['start_time'])) {
            if (empty($data['start_time'])) {
                $this->errors['start_time'] = "Start time is required.";
            }
        }

        // Validate Duration
        if (isset($data['duration'])) {
            if (empty($data['duration'])) {
                $this->errors['duration'] = "Duration is required.";
            } elseif (!is_numeric($data['duration']) || $data['duration'] <= 0) {
                $this->errors['duration'] = "Duration must be a positive number.";
            }
        }

        // Validate Location
        if (isset($data['location'])) {
            if (empty(trim($data['location']))) {
                $this->errors['location'] = "Location is required.";
            }
        }

        // Validate Price
        if (isset($data['price'])) {
            if ($data['price'] === '') {
                $this->errors['price'] = "Ticket price is required.";
            } elseif (!is_numeric($data['price']) || $data['price'] < 0) {
                $this->errors['price'] = "Ticket price must be a non-negative number.";
            }
        }

        return empty($this->errors);
    }
    
    // Removed invalid variable declaration
    public function getEventByIdd($event_id)
    {
        $query = "SELECT * FROM event WHERE event_id = :event_id";
        $result = $this->query($query, ['event_id' => $event_id], true);
    
       return $result;
    }


    public function getEventParticipants($event_id)
    {
        $query = "SELECT 
                    ep.id, ep.full_name, ep.nic, 
                    ep.is_member, ep.membership_number, ep.contact_no,
                    e.event_id, e.description as event_name
                  FROM event_participants ep
                  JOIN event e ON ep.event_id = e.event_id
                  WHERE ep.event_id = :event_id
                  ORDER BY ep.id";
                  
        return $this->query($query, ['event_id' => $event_id]);
    }

}
