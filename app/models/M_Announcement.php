<?php

//Announcement class
class M_Announcement
{

    use Model;

    protected $table = 'announcement';
    protected $allowedColumns = [
        'id',
        'subject',
        'description',
        'created_by',
        'created_date',
        'created_time'
    ];

    public function validate($data)
    {

        $this->errors = [];

        if (empty($data['subject'])) {
            $this->errors['subject'] = "Subject is required";
        }

        if (empty($data['description'])) {
            $this->errors['announcement'] = "Announcement description is required";
        }

        return empty($this->errors);

    }
    
    public function findAllWithAdminNames($limit) {
        // SQL query to join announcement and admin tables
        $query = "
            SELECT a.id, a.subject, a.description, a.created_by, a.created_date, a.created_time,
                    CONCAT(ad.first_name, ' ', ad.last_name) AS admin_name, ad.image
            FROM announcement a
            LEFT JOIN admin ad ON a.created_by = ad.admin_id
            ORDER BY a.created_date DESC, a.created_time DESC 
            LIMIT $limit
        ";

        // Execute the query using the query function from the trait
        return $this->query($query);
    }

    public function findAnnouncementById($announcement_id) {
        $sql = "
            SELECT a.id, a.subject, a.description, a.created_by, a.created_date, a.created_time,
                    CONCAT(ad.first_name, ' ', ad.last_name) AS admin_name
            FROM announcement a
            LEFT JOIN admin ad ON a.created_by = ad.admin_id
            WHERE a.id = :id
        ";

        // Execute the query using the get_row function from the trait
        return $this->get_row($sql, ['id' => $announcement_id]);
    }

    public function findAllWithAdminDetails() {
        // SQL query to join announcement and admin tables
        $query = "
            SELECT a.id, a.subject, a.description, a.created_by, a.created_date, a.created_time, ad.first_name, ad.last_name, ad.email_address, ad.image
            FROM announcement a
            LEFT JOIN admin ad ON a.created_by = ad.admin_id
            ORDER BY a.created_date DESC, a.created_time DESC
        ";

        // Execute the query using the query function from the trait
        return $this->query($query);
    }
}
    
?>