<?php
    class M_EventParticipants
    {
        use Model;

        protected $table = 'event_participants';
        protected $allowedColumns = [
            'id',
            'full_name',
            'event_id',
            'nic',
            'is_member',    
            'membership_number',
            'contact_number',
        ];

        public function CountUniqueParticipants()
        {
            $query = "SELECT COUNT(DISTINCT nic) AS unique_participants FROM $this->table";
            $result = $this->get_row($query);
            return $result ? $result->unique_participants : 0;
        }
        
    }
?>