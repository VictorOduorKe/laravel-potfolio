<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'username',
        'email',
        'password',
        'login_token',
        'login_token_expires_at',
        'api_token',
    ];

    protected $hidden = [
        'password',
        'login_token',
        'api_token',
    ];

    protected function casts(): array
    {
        return [
            'login_token_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
