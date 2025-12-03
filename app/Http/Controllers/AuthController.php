<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SecurityQuestion;
use App\Models\PasswordHistory;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Create a new user (Admin onboarding).
     */
    public function createUser(Request $request)
    {
        // TODO: Add admin authorization check here
        // For now, assuming this is called by admin

        $validator = Validator::make($request->all(), [
            'employee_code' => 'required|string|unique:users,employee_code|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'other_names' => 'nullable|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'job_title' => 'nullable|string|max:255',
            'role' => 'required|in:executive,head_of_department,team_lead,manager,staff',
            'access_scope' => 'required|in:global,branch,department',
            'department_id' => 'nullable|exists:departments,id',
            'branch' => 'nullable|string|max:255',
            'sub_branch' => 'nullable|string|max:255',
            'sector' => 'nullable|string|max:255',
            'reports_to_user_id' => 'nullable|exists:users,id',
            'password' => 'nullable|string|min:16|regex:/^(?=.*[a-z].*[a-z])(?=.*[A-Z].*[A-Z])(?=.*\d.*\d)(?=.*[@$!%*?&(){}[\]|;:,.<>?].*[@$!%*?&(){}[\]|;:,.<>?])(?!.*(.)\1{2,})[A-Za-z\d@$!%*?&(){}[\]|;:,.<>?]/', // Optional for onboarding
            'send_activation_email' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'employee_code' => $request->employee_code,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'other_names' => $request->other_names,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'job_title' => $request->job_title,
            'role' => $request->role,
            'access_scope' => $request->access_scope,
            'department_id' => $request->department_id,
            'branch' => $request->branch,
            'sub_branch' => $request->sub_branch,
            'sector' => $request->sector,
            'reports_to_user_id' => $request->reports_to_user_id,
            'password' => $request->password ? Hash::make($request->password) : null, // Null if not set
            'is_active' => true,
        ]);

        // Send email verification if requested
        if ($request->send_activation_email) {
            $user->sendEmailVerificationNotification();
        }

        return response()->json([
            'message' => 'User created successfully.' . ($request->send_activation_email ? ' Activation email sent.' : ''),
            'user' => $user,
        ], 201);
    }

    /**
     * Login user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // email or employee_code
            'password' => 'required|string',
        ]);

        // Check if account is locked
        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->login)
                  ->orWhere('employee_code', $request->login);
        })->first();

        if ($user && $user->locked_until && now()->lessThan($user->locked_until)) {
            $minutes = now()->diffInMinutes($user->locked_until);
            return response()->json([
                'message' => 'Account is locked due to too many failed attempts. Try again in ' . $minutes . ' minutes.'
            ], 423);
        }

        // Rate limiting
        $key = 'login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'message' => 'Too many login attempts. Try again in ' . $seconds . ' seconds.'
            ], 429);
        }

        if (!$user || !$user->password || !Hash::check($request->password, $user->password)) {
            RateLimiter::hit($key);

            // Log failed login attempt
            $machine_ip = $request->ip();
            LoginLog::create([
                'user_id' => $user ? $user->id : null,
                'login_attempt' => $request->login,
                'ip_address' => $machine_ip,
                'user_agent' => $request->userAgent(),
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
                    return response()->json(['message' => 'Account locked due to too many failed attempts.'], 423);
                }
            }

            throw ValidationException::withMessages([
                'login' => ['Invalid credentials.'],
            ]);
        }

        // Reset failed attempts on successful login
        if ($user->failed_login_attempts > 0) {
            $user->update(['failed_login_attempts' => 0, 'locked_until' => null]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Account is deactivated.'], 403);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified. Please verify your email first.'], 403);
        }

        RateLimiter::clear($key);

        // Log successful login
        $machine_ip = $request->ip();
        LoginLog::create([
            'user_id' => $user->id,
            'login_attempt' => $request->login,
            'ip_address' => $machine_ip,
            'user_agent' => $request->userAgent(),
            'successful' => true,
        ]);

        // Store session fingerprint for hijacking detection
        session([
            'auth_ip' => $request->ip(),
            'auth_user_agent' => $request->userAgent(),
        ]);

        // Revoke existing tokens for security
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        // Clear session fingerprint
        session()->forget(['auth_ip', 'auth_user_agent']);

        return response()->json(['message' => 'Logged out successfully']);
    }

    /**
     * Get authenticated user.
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Refresh token.
     */
    public function refresh(Request $request)
    {
        $user = $request->user();

        // Revoke current token
        $request->user()->currentAccessToken()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Token refreshed',
            'token' => $token,
        ]);
    }

    /**
     * Send password reset link.
     */
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Password reset link sent to your email.']);
        }

        return response()->json(['message' => 'Unable to send reset link.'], 500);
    }

    /**
     * Reset password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:16|confirmed|regex:/^(?=.*[a-z].*[a-z])(?=.*[A-Z].*[A-Z])(?=.*\d.*\d)(?=.*[@$!%*?&(){}[\]|;:,.<>?].*[@$!%*?&(){}[\]|;:,.<>?])(?!.*(.)\1{2,})[A-Za-z\d@$!%*?&(){}[\]|;:,.<>?]/',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Check password history
                $recentPasswords = $user->passwordHistories()->latest()->take(5)->pluck('password')->toArray();
                foreach ($recentPasswords as $oldPassword) {
                    if (Hash::check($password, $oldPassword)) {
                        throw ValidationException::withMessages([
                            'password' => ['You cannot reuse a recent password.'],
                        ]);
                    }
                }

                // Store current password in history
                PasswordHistory::create([
                    'user_id' => $user->id,
                    'password' => $user->password,
                ]);

                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                // Revoke all tokens for security
                $user->tokens()->delete();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password reset successfully.']);
        }

        return response()->json(['message' => 'Invalid token or email.'], 422);
    }


    /**
     * Resend email verification.
     */
    public function resendVerification(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent.']);
    }

    /**
     * Verify email.
     */
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'hash' => 'required|string',
        ]);

        $user = User::findOrFail($request->id);

        if (!hash_equals((string) $request->id, (string) $user->getKey())) {
            return response()->json(['message' => 'Invalid verification link.'], 400);
        }

        if (!hash_equals($request->hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $user->markEmailAsVerified();

        return response()->json(['message' => 'Email verified successfully.']);
    }


    /**
     * Get user's security questions (questions only, no answers).
     */
    public function getSecurityQuestions(Request $request)
    {
        $questions = $request->user()->securityQuestions()->select('id', 'question')->get();

        return response()->json($questions);
    }

    /**
     * Update user's security questions.
     */
    public function updateSecurityQuestions(Request $request)
    {
        $request->validate([
            'security_questions' => 'required|array|min:2|max:5',
            'security_questions.*.question' => 'required|string|max:255',
            'security_questions.*.answer' => 'required|string|max:255',
        ]);

        $user = $request->user();

        // Delete existing questions
        $user->securityQuestions()->delete();

        // Create new ones
        foreach ($request->security_questions as $sq) {
            SecurityQuestion::create([
                'user_id' => $user->id,
                'question' => $sq['question'],
                'answer' => Hash::make(strtolower(trim($sq['answer']))),
            ]);
        }

        return response()->json(['message' => 'Security questions updated successfully']);
    }

    /**
     * Change password for authenticated user.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:16|confirmed|regex:/^(?=.*[a-z].*[a-z])(?=.*[A-Z].*[A-Z])(?=.*\d.*\d)(?=.*[@$!%*?&(){}[\]|;:,.<>?].*[@$!%*?&(){}[\]|;:,.<>?])(?!.*(.)\1{2,})[A-Za-z\d@$!%*?&(){}[\]|;:,.<>?]/',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 400);
        }

        // Check password history (last 5 passwords)
        $recentPasswords = $user->passwordHistories()->latest()->take(5)->pluck('password')->toArray();
        foreach ($recentPasswords as $oldPassword) {
            if (Hash::check($request->password, $oldPassword)) {
                return response()->json(['message' => 'You cannot reuse a recent password.'], 400);
            }
        }

        // Store current password in history
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Revoke all tokens for security
        $user->tokens()->delete();

        return response()->json(['message' => 'Password changed successfully. Please login again.']);
    }

    /**
     * Set initial password for newly onboarded user.
     */
    public function setInitialPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:16|confirmed|regex:/^(?=.*[a-z].*[a-z])(?=.*[A-Z].*[A-Z])(?=.*\d.*\d)(?=.*[@$!%*?&(){}[\]|;:,.<>?].*[@$!%*?&(){}[\]|;:,.<>?])(?!.*(.)\1{2,})[A-Za-z\d@$!%*?&(){}[\]|;:,.<>?]/',
            'security_questions' => 'required|array|min:2|max:5',
            'security_questions.*.question' => 'required|string|max:255',
            'security_questions.*.answer' => 'required|string|max:255',
        ]);

        $user = $request->user();

        // Check if password is already set
        if ($user->password) {
            return response()->json(['message' => 'Password already set.'], 400);
        }

        // Check password history (should be empty for new users, but just in case)
        $recentPasswords = $user->passwordHistories()->latest()->take(5)->pluck('password')->toArray();
        foreach ($recentPasswords as $oldPassword) {
            if (Hash::check($request->password, $oldPassword)) {
                return response()->json(['message' => 'You cannot reuse a recent password.'], 400);
            }
        }

        // Set password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Create security questions
        foreach ($request->security_questions as $sq) {
            SecurityQuestion::create([
                'user_id' => $user->id,
                'question' => $sq['question'],
                'answer' => Hash::make(strtolower(trim($sq['answer']))),
            ]);
        }

        // Store password in history
        PasswordHistory::create([
            'user_id' => $user->id,
            'password' => $user->password,
        ]);

        return response()->json(['message' => 'Password and security questions set successfully.']);
    }
}