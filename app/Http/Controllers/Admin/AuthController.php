<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\MailService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AuthController extends Controller
{
    /**
     * Show admin login step 1 (Email + Password)
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle admin login step 1 and dispatch OTP
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Invalid admin credentials provided.',
            ]);
        }

        // Generate cryptographically secure 6-digit OTP
        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(15);

        $admin->update([
            'login_token' => $otp,
            'login_token_expires_at' => $expiresAt,
        ]);

        // Send OTP email
        $sent = MailService::sendOtp($admin->email, $otp, 15);
        if (!$sent) {
            $admin->update([
                'login_token' => null,
                'login_token_expires_at' => null,
            ]);
            Log::error("Authentication aborted: Failed sending OTP email to {$admin->email}.");
            return back()->withInput($request->only('email'))->withErrors([
                'email' => 'Failed to deliver verification code to your email. Please verify mail server settings.',
            ]);
        }

        session(['admin_login_email' => $admin->email]);

        return redirect()->route('admin.verify')->with('status', 'A 6-digit verification code has been dispatched to your email address.');
    }

    /**
     * Show admin login step 2 (OTP Verification)
     */
    public function showVerify(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if (!session()->has('admin_login_email')) {
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Please enter your email and password first.',
            ]);
        }

        return view('admin.auth.verify', [
            'email' => session('admin_login_email'),
        ]);
    }

    /**
     * Handle admin login step 2 (Verify OTP)
     */
    public function verify(Request $request): RedirectResponse
    {
        $email = session('admin_login_email') ?? $request->input('email');

        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $admin = Admin::where('email', $email)
            ->where('login_token', $request->input('otp'))
            ->where('login_token_expires_at', '>', now())
            ->first();

        if (!$admin) {
            return back()->withErrors([
                'otp' => 'Invalid or expired verification code. Please try again or request a new code.',
            ]);
        }

        // Clear token
        $admin->update([
            'login_token' => null,
            'login_token_expires_at' => null,
        ]);

        session()->forget('admin_login_email');

        Auth::guard('admin')->login($admin);
        $request->session()->regenerate();

        Log::info("Admin {$admin->email} authenticated successfully.");

        return redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back to your administration dashboard!');
    }

    /**
     * Resend verification code
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = session('admin_login_email');
        if (!$email) {
            return redirect()->route('admin.login');
        }

        $admin = Admin::where('email', $email)->first();
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        $otp = (string) random_int(100000, 999999);
        $admin->update([
            'login_token' => $otp,
            'login_token_expires_at' => now()->addMinutes(15),
        ]);

        $sent = MailService::sendOtp($admin->email, $otp, 15);
        if (!$sent) {
            Log::error("Failed resending OTP email to {$admin->email}.");
            return back()->with('error', 'Failed to dispatch a fresh verification code. Please check mail server settings.');
        }

        return back()->with('status', 'A fresh verification code has been dispatched to your email.');
    }

    /**
     * Show the admin password reset request form.
     */
    public function showForgotPassword(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.forgot-password');
    }

    /**
     * Send a password reset link to the administrator's email.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim((string) $request->input('email')));
        $admin = Admin::where('email', $email)->first();

        $genericMessage = 'If an administrator account is associated with this email address, a secure password-reset link has been dispatched.';

        if ($admin) {
            // Generate single-use, time-limited token using Laravel's password broker for admins
            $token = Password::broker('admins')->createToken($admin);

            $expiresInMinutes = (int) config('auth.passwords.admins.expire', 60);
            $sent = MailService::sendPasswordResetLink($admin, $token, $expiresInMinutes);

            if (!$sent) {
                Log::error('Failed to dispatch password reset email to administrator.', [
                    'email' => $admin->email,
                ]);

                if ($request->wantsJson()) {
                    return response()->json([
                        'message' => 'Unable to dispatch reset email at this moment. Please check server logs.',
                    ], 500);
                }

                return back()->withInput($request->only('email'))->withErrors([
                    'email' => 'Unable to dispatch reset email at this moment. Please try again later.',
                ]);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $genericMessage,
            ]);
        }

        return back()->with('status', $genericMessage);
    }

    /**
     * Display the password reset view for the given token.
     */
    public function showResetPassword(Request $request, string $token): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', old('email')),
        ]);
    }

    /**
     * Reset the administrator's password.
     */
    public function resetPassword(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                PasswordRule::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $credentials = $request->only('email', 'password', 'password_confirmation', 'token');
        $credentials['email'] = strtolower(trim((string) $credentials['email']));

        $status = Password::broker('admins')->reset(
            $credentials,
            function (Admin $admin, string $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                    'login_token' => null,
                    'login_token_expires_at' => null,
                ]);

                $admin->save();

                event(new PasswordReset($admin));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Invalidate any existing sessions for security
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info("Administrator password reset successfully for {$credentials['email']}.");

            $successMessage = 'Your password has been successfully reset. Please log in with your new credentials.';

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $successMessage,
                ]);
            }

            return redirect()->route('admin.login')->with('status', $successMessage);
        }

        $errorMessage = __($status);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $errorMessage,
                'errors' => ['email' => [$errorMessage]],
            ], 422);
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => $errorMessage,
        ]);
    }

    /**
     * Allow an authenticated administrator to dispatch a reset link to their registered email.
     */
    public function sendResetLinkFromSettings(Request $request): RedirectResponse|JsonResponse
    {
        /** @var Admin|null $admin */
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            abort(403, 'Unauthorized administrator action.');
        }

        $token = Password::broker('admins')->createToken($admin);
        $expiresInMinutes = (int) config('auth.passwords.admins.expire', 60);
        $sent = MailService::sendPasswordResetLink($admin, $token, $expiresInMinutes);

        if (!$sent) {
            Log::error('Settings action failed: Could not dispatch password reset email.', [
                'email' => $admin->email,
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to dispatch password reset link. Please check server mail settings.',
                ], 500);
            }

            return back()->with('error', 'Failed to dispatch password reset email. Please verify mail server settings.');
        }

        $message = "A secure password-reset link has been dispatched to your registered email address ({$admin->email}).";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been logged out securely.');
    }
}
