<?php

    class M_Supplements{

        use Model;

        protected $table = 'supplement';

        // List of allowed columns for insertion or updates
        protected $allowedColumns = [
            'supplement_id',
            'name',
            'file',
            'quantity_available',
            'quantity_sold'
        ];

        public function getLastInsertId()
        {
            $query = "SELECT supplement_id FROM $this->table ORDER BY supplement_id DESC LIMIT 1";
            
            // Execute the query to fetch the last inserted schedule_id
            $result = $this->get_row($query);
            
            // Return the retrieved schedule_id
            return $result ? $result->supplement_id : null;
        }

        public function getAvailableQuantity($supplement_id)
        {
            $query = "SELECT quantity_available FROM $this->table WHERE supplement_id = $supplement_id";
            
            // Execute the query to fetch the last inserted schedule_id
            $result = $this->get_row($query);
            
            // Return the retrieved schedule_id
            return $result ? $result->quantity_available : null;
        }

        public function getSoldQuantity($supplement_id)
        {
            $query = "SELECT quantity_sold FROM $this->table WHERE supplement_id = $supplement_id";
            
            // Execute the query to fetch the last inserted schedule_id
            $result = $this->get_row($query);
            
            // Return the retrieved schedule_id
            return $result ? $result->quantity_sold : null;
        }

    }

?>