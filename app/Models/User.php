<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'other_names',
        'email',
        'phone_number',
        'job_title',
        'role',
        'access_scope',
        'department_id',
        'branch',
        'sub_branch',
        'sector',
        'reports_to_user_id',
        'password',
        'is_active',
        'failed_login_attempts',
        'locked_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'locked_until' => 'datetime',
    ];

    public function securityQuestions()
    {
        return $this->hasMany(SecurityQuestion::class);
    }

    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }
}
