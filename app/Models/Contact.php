<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    public $timestamps = false; // Has only created_at

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'ip_address',
        'message',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
