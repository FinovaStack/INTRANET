<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'question',
        'answer',
    ];

    protected $hidden = [
        'answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}