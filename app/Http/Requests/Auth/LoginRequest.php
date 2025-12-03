<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Models\LoginLog;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // email or employee_code
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Find user by email or employee_code
        $user = User::where(function ($query) {
            $query->where('email', $this->input('login'))
                  ->orWhere('employee_code', $this->input('login'));
        })->first();

        // Check if account is locked
        if ($user && $user->locked_until && now()->lessThan($user->locked_until)) {
            $minutes = now()->diffInMinutes($user->locked_until);
            throw ValidationException::withMessages([
                'login' => ['Account is locked due to too many failed attempts. Try again in ' . $minutes . ' minutes.'],
            ]);
        }

        if (!$user || !$user->password || !password_verify($this->input('password'), $user->password)) {
            RateLimiter::hit($this->throttleKey());

            // Log failed login attempt
            LoginLog::create([
                'user_id' => $user ? $user->id : null,
                'login_attempt' => $this->input('login'),
                'ip_address' => $this->ip(),
                'user_agent' => $this->userAgent(),
                'successful' => false,
                'failure_reason' => $user ? 'Invalid password' : 'User not found',
            ]);

            // Increment failed attempts and lock if necessary
            if ($user) {
                $user->increment('failed_login_attempts');
                if ($user->failed_login_attempts >= 5) {
                    $user->update([
                        'locked_until' => now()->addMinutes(30),
                        'failed_login_attempts' => 0,
                    ]);
                    throw ValidationException::withMessages([
                        'login' => ['Account locked due to too many failed attempts.'],
                    ]);
                }
            }

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        // Reset failed attempts on successful login
        if ($user->failed_login_attempts > 0) {
            $user->update(['failed_login_attempts' => 0, 'locked_until' => null]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'login' => ['Account is deactivated.'],
            ]);
        }

        if (!$user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'login' => ['Email not verified. Please verify your email first.'],
            ]);
        }

        // Log successful login
        LoginLog::create([
            'user_id' => $user->id,
            'login_attempt' => $this->input('login'),
            'ip_address' => $this->ip(),
            'user_agent' => $this->userAgent(),
            'successful' => true,
        ]);

        // Store session fingerprint for hijacking detection
        session([
            'auth_ip' => $this->ip(),
            'auth_user_agent' => $this->userAgent(),
        ]);

        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('login')).'|'.$this->ip());
    }
}
