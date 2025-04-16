<?php

//Announcement class
class M_Announcement
{

    use Model;

    protected $table = 'announcement';
    protected $allowedColumns = [
        'announcement_id',
        'subject',
        'announcement',
        'created_date',
        'created_time'
    ];

    public function validate($data)
    {

        $this->errors = [];

        if (empty($data['subject'])) {
            $this->errors['subject'] = "Subject is required";
        }

        if (empty($data['announcement'])) {
            $this->errors['announcement'] = "Announcement description is required";
        }

        return empty($this->errors);

    }
}