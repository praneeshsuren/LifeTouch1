<?php
class M_PasswordReset
{
    use Model;

    protected $table = 'password_reset_tokens';
    protected $allowedColumns = [
        'id',
        'user_id',
        'token',
        'expires_at',
        'created_at'
    ];

    

    
}
