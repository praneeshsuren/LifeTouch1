<?php

//User class
class M_User
{

    use Model;

    protected $table = 'users';
    protected $allowedColumns = [
        'user_id',
        'username',
        'password'
    ];

    public function usernameExists($username)
    {
        $query = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $result = $this->query($query, ['username' => $username]);

        return !empty($result); // Returns true if the username exists, false otherwise
    }


    public function validate($data)
    {
        $this->errors = [];

        // Validate username
        if (empty($data['username'])) {
            $this->errors['username'] = 'Username is required';
        } else {
            // Validate username format
            if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $data['username'])) {
                $this->errors['username'] = 'Username must be 3-20 characters long and contain only letters, numbers, or underscores';
            } else {
                // Check if username already exists
                if ($this->usernameExists($data['username'])) {
                    $this->errors['username'] = 'Username already exists. Please choose another one.';
                }
            }
        }

        // Validate password
        if (empty($data['password'])) {
            $this->errors['password'] = 'Password is required';
        } else {
            if (strlen($data['password']) < 6) {
                $this->errors['password'] = 'Password must be at least 6 characters long';
            }
        }

        // Validate confirm password
        if (empty($data['confirm_password'])) {
            $this->errors['confirm_password'] = 'Confirm password is required';
        } else {
            if ($data['password'] !== $data['confirm_password']) {
                $this->errors['confirm_password'] = 'Passwords do not match';
            }
        }

        // If there are no errors, return true; otherwise, return false.
        return empty($this->errors);
    }

    public function findByUserId($user_id)
    {
        $data = ['user_id' => $user_id];
        return $this->first($data);
    }

    public function updatePassword($user_id, $new_password)
    {
        $data = [
            'user_id' => $user_id,
            'password' => $new_password
        ];

        return $this->update($user_id, $data, 'user_id');
    }
}
