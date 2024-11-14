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
        'date',
        'time'
    ];

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['subject'])) {
            $this->errors['subject'] = "Subject is required";
        }

        if (empty($data['announcement'])) {
            $this->errors['announcement'] = "Announcement content is required";
        }

        if (empty($data['date'])) {
            $this->errors['date'] = "Date is required";
        }

        if (empty($data['time'])) {
            $this->errors['time'] = "Time is required";
        }

        if (empty($this->errors)) {
            return true;
        }
        return false;
    }
}
