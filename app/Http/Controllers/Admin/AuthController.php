<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Services\MailService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

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
