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

        public function searchSupplements($query) {
            // Ensure the query is sanitized and converted to lowercase for case-insensitive search
            $query = "%" . strtolower($query) . "%";  // Adding % for LIKE search
        
            // SQL query to search for supplements by name in a case-insensitive manner
            $sql = "SELECT * FROM $this->table WHERE LOWER(name) LIKE :query"; 
        
            // Prepare the data array for parameter binding
            $data = ['query' => $query];
        
            // Use the query method from your Database trait to execute the query
            return $this->query($sql, $data);
        }
        
        public function countAllSupplements() {
            // SQL query to count all supplements
            $sql = "SELECT COUNT(*) as total FROM $this->table";
        
            // Execute the query and fetch the result
            $result = $this->get_row($sql);
        
            // Return the total count
            return $result ? $result->total : 0;
        }

        public function getSupplement($supplementId) {
            // SQL query to fetch supplements with pagination
            $query = "SELECT * FROM $this->table WHERE supplement_id = :supplementId";
        
            // Prepare the data array for parameter binding
            $data = [
                'supplementId' => $supplementId
            ];
        
            // Use the query method from your Database trait to execute the query
            $result = $this->query($query, $data);

            // Return the result
            if ($result) {
                return $result[0]; // Return the first result
            } else {
                return null; // No results found
            }
        }

        public function getSupplementByName($name)
        {
            $query = "SELECT * FROM $this->table WHERE name LIKE :name";

            // Prepare the data array for parameter binding
            $data = [
                'name' => $name
            ];

            // Use the query method from your Database trait to execute the query
            $result = $this->query($query, $data);

            // Return the result
            if ($result) {
                return $result[0]; // Return the first result
            } else {
                return null; // No results found
            }

        }

        public function getSupplementById($id)
        {
            $data = ['supplement_id' => $id];
            return $this->first($data);
        }

        
        

    }

?>