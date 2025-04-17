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

    public function findByEventId($eventId) {
        $data = ['event_id' => $eventId];
        return $this->first($data);  // Use the `first` method to get the first matching record
    }

    public function validate($data)
    {

        $this->errors = [];

        return empty($this->errors);

    }
}
