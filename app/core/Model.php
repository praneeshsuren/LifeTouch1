<?php

// Main Model Trait
trait Model
{

    use Database;

    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = "desc";
    protected $order_column = "created_at";
    public $errors = [];


    public function findAll()
    {
        $query = " select * from $this->table order by $this->order_column $this->order_type limit $this->limit offset $this->offset";
        return $this->query($query);
    }

    public function where($data, $data_not = [], $order_column)
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . "  && ";
        }

        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . "  && ";
        }

        $query = trim($query, " && ");
        //echo $order_column;
        $query .= " order by $order_column $this->order_type limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);

        return $this->query($query, $data);
    }

    public function first($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keys_not = array_keys($data_not);
        $query = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . "  && ";
        }

        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . "  && ";
        }

        $query = trim($query, " && ");

        $query .= " limit $this->limit offset $this->offset";
        $data = array_merge($data, $data_not);

        $result = $this->query($query, $data);
        if ($result) {
            return $result[0];
        }

        return false;
    }

    public function insert($data)
    {

        //remove columns that are not part of the allowed columns
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "insert into $this->table (" . implode(",", $keys) . ") values (:" . implode(",:", $keys) . ")";

        $this->query($query, $data);

        return false;
    }

    public function update($id, $data, $id_column = 'id')
    {
        // Remove columns that are not part of the allowed columns
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        // Ensure there is data to update
        if (empty($data)) {
            return false;
        }
    
        // Prepare the update query
        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";
    
        foreach ($keys as $key) {
            $query .= "$key = :$key, ";
        }
    
        $query = rtrim($query, ", "); // Remove trailing comma
        $query .= " WHERE $id_column = :$id_column";
    
        // Add the ID to the data array for the WHERE clause
        $data[$id_column] = $id;
    
        // Execute the query and return the result
        return $this->query($query, $data);
    }
    

    public function delete($id, $id_column = 'id')
    {
        $data[$id_column] = $id;
        $query = "DELETE FROM $this->table WHERE $id_column = :$id_column";

        // Execute the query and store the result
        $result = $this->query($query, $data);

        // Assuming $this->query() returns a boolean or similar indicating success/failure
        if ($result) {
            return true; // Successful deletion
        }

        return false; // Deletion failed
    }


    public function countAll()
    {
        $query = "SELECT COUNT(*) as total FROM $this->table";
        $result = $this->query($query);

        if ($result && !empty($result)) {
            return $result[0]->total;
        }

        return 0;
    }
}
