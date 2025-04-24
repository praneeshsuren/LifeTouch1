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

        return empty($this->errors);

    }
    // Removed invalid variable declaration
    //public function getEventById($event_id)
    //{
      //  $query = "SELECT * FROM event WHERE event_id = :event_id";
        //$result = $this->query($query, ['event_id' => $event_id], true);
    
       //return $result;
    //}


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
