<?php

    //Admin class
    class M_Contact{

        use Model;

        protected $table = 'contact';
        protected $allowedColumns = [
            'id',
            'name',
            'email',
            'msg',
            'created_at'
        ];

        public function findInquityById($inquiryId) {
            $data = ['id' => $inquiryId];
            return $this->first($data);
        }

        public function validate($data) {
            $this->errors = [];

            if (empty($data['name'])) {
                $this->errors['name'] = 'name is required';
            } 
            if (empty($data['email'])) {
                $this->errors['email'] = 'email is required';
            } 
            if (empty($data['msg'])) {
                $this->errors['msg'] = 'msg is required';
            } 
            
            // If there are no errors, return true; otherwise, return false.
            return empty($this->errors);
        }

        // Method to get errors after validation
        public function getErrors()
        {
            return $this->errors;
        }
        
        public function countAllContactsInLast30Days() {
            $date30DaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

            $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE created_at >= :date30DaysAgo";

            $result = $this->query($query, ['date30DaysAgo' => $date30DaysAgo]);

            if ($result && !empty($result)) {
                return $result[0]->total;
            }
            return 0;
        }
    }