<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'login_attempt',
        'ip_address',
        'user_agent',
        'successful',
        'failure_reason',
    ];

    protected $casts = [
        'successful' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}