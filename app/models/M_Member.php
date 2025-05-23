<?php

    //Member class
    class M_Member{

        use Model;

        protected $table = 'member';
        protected $allowedColumns = [
            'member_id',
            'first_name',
            'last_name',
            'date_of_birth',
            'NIC_no',
            'home_address',
            'height',
            'weight',
            'contact_number',
            'gender',
            'email_address',
            'status',
            'image',
            'membershipPlan_id',
            'id',
            'health_conditions',
            'created_at'
        ];

        public function findByMemberId($memberId) {
            $data = ['member_id' => $memberId];
            return $this->first($data);  
        }

        public function validate($data){
            $this->errors = [];

            if (empty($data['first_name'])) {
                $this->errors['first_name'] = 'First name is required';
            }

            if (empty($data['last_name'])) {
                $this->errors['last_name'] = 'Last name is required';
            }

            if (empty($data['date_of_birth'])) {
                $this->errors['date_of_birth'] = 'Date of birth is required';
            }

            if (empty($data['home_address'])) {
                $this->errors['home_address'] = 'Home address is required';
            }

            if (empty($data['contact_number'])) {
                $this->errors['contact_number'] = 'Contact number is required';
            } elseif (!preg_match('/^\d{10}$/', $data['contact_number'])) {
                $this->errors['contact_number'] = 'Contact number must be a 10-digit number';
            }
            

            if (empty($data['email_address'])) {
                $this->errors['email_address'] = 'Email address is required';
            } else {
                if (!filter_var($data['email_address'], FILTER_VALIDATE_EMAIL)) {
                    $this->errors['email_address'] = 'Invalid email address';
                }
            }

            if (empty($data['gender'])) {
                $this->errors['gender'] = 'Gender is required';
            }
            if (empty($data['membership_plan'])) {
                $this->errors['membership_plan'] = 'Membership Plan is required';
            }

            if (empty($data['height'])) {
                $this->errors['height'] = 'Height is required';
            } elseif ($data['height'] < 0) {
                $this->errors['height'] = 'Height cannot be negative';
            }
            
            if (empty($data['weight'])) {
                $this->errors['weight'] = 'Weight is required';
            } elseif ($data['weight'] < 0) {
                $this->errors['weight'] = 'Weight cannot be negative';
            }
            

            if (empty($data['NIC_no'])) {
                $this->errors['NIC_no'] = 'NIC number is required';
            } elseif (strlen($data['NIC_no']) > 12) {
                $this->errors['NIC_no'] = 'NIC number cannot exceed 12 characters';
            }

            return empty($this->errors);
            
        }

        public function emailExists($email, $excludeId = null) {
            $data = ['email_address' => $email];
        
            // Prepare conditions for excluding the current member
            $data_not = [];
            if ($excludeId) {
                $data_not['member_id'] = $excludeId;
            }
        
            // Use the where function to query the database
            $result = $this->where($data, $data_not, 'email_address');
        
            // If we found any result, it means the email exists and is used by another member
            return !empty($result);
        }

        public function countRecentMembers(){
            $date30DaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

            $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE created_at >= :date30DaysAgo";

            $result = $this->query($query, ['date30DaysAgo' => $date30DaysAgo]);

            if ($result && !empty($result)) {
                return $result[0]->total;
            }
    
            return 0;
        }

        public function getLastMemberId() {
            // Get the last member's ID from the database
            $query = "SELECT id FROM {$this->table} ORDER BY id DESC LIMIT 1";
            $result = $this->query($query);
        
            if ($result && !empty($result)) {
                return $result[0];  // Return the last member record
            }
        
            return null;  // Return null if no records are found
        }

        public function updateMembershipStatus($member_id, $status) {
            // Prepare the query to update the membership status
            $query = "UPDATE $this->table SET status = :status WHERE member_id = :member_id";
        
            $params = [
                'status' => $status,
                'member_id' => $member_id
            ];
        
            // Execute the query to update the status
            return $this->query($query, $params);
        }
        
    }