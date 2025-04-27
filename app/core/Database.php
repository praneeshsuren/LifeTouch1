<?php
trait Database
{

    private function connect()
    {
        $string = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $con = new PDO($string, DB_USER, DB_PASSWORD);
        return $con;
    }

    public function query($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $check = $stm->execute($data);

        if (!$check) {
            return false;
        }

        // Check if it's a SELECT query
        if (preg_match('/^\s*SELECT/i', $query)) {
            return $stm->fetchAll(PDO::FETCH_OBJ);
        }

        // For INSERT/UPDATE/DELETE, just return true if successful
        return true;
    }


    public function get_row($query, $data = [])
    {
        $con = $this->connect();
        $stm = $con->prepare(($query));

        $check = $stm->execute($data);
        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count(($result))) {
                return $result[0];
            }
        }

        return false;
    }
}
