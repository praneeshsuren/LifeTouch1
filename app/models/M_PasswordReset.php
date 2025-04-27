<?php
class M_PasswordReset
{
    use Model;

    protected $table = 'password_reset_tokens';
    protected $allowedColumns = [
        'id',
        'email',
        'token',
        'expires_at',
        'created_at'
    ];

    public function deleteByEmail($email)
    {
        $query = "DELETE FROM {$this->table} WHERE email = :email";
        return $this->query($query, ['email' => $email]);
    }

    public function findValidToken($token)
    {
        $query = "SELECT * FROM {$this->table} WHERE token = :token AND expires_at > NOW() LIMIT 1";
        return $this->query($query, ['token' => $token])[0] ?? false;
    }
}
