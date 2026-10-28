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

    /**
     * Send password reset notification to admin.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\AdminResetPasswordNotification($token));
    }

    /**
     * Admins authenticate with two-factor OTP and do not use remember_token.
     */
    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        // No-op: admins table uses OTP two-factor authentication instead of remember_token
    }

    public function getRememberTokenName(): string
    {
        return '';
    }
}
