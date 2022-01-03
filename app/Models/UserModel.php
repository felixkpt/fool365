<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model{
    protected $table = 'users';

    protected $allowedFields = ['username',	'email', 'phone', 'country', 'password',
        'verification_key', 'is_email_verified',
        'created_at',
        'last_login',
        'reset_token',
        'profile_photo'];

}

