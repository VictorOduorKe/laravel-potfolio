<?php

namespace App\Services;

use App\Mail\AdminOtpMail;
use App\Mail\ContactConfirmationVisitorMail;
use App\Mail\ContactReceivedAdminMail;
use App\Models\Contact;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailService
{
    /**
     * Send OTP email to Admin using Laravel native Mail.
     */
    public static function sendOtp(string $toEmail, string $otp, int $expiresInMinutes = 15): bool
    {
        try {
            Mail::to($toEmail)->send(new AdminOtpMail($otp, $expiresInMinutes));
            Log::info("Admin OTP verification email successfully dispatched to {$toEmail}.");
            return true;
        } catch (\Throwable $e) {
            Log::error("Failed sending Admin OTP email to {$toEmail}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send contact submission notification to Admin and acknowledgment to Visitor using Laravel native Mail.
     */
    public static function handleContactSubmission(Contact $contact): bool
    {
        $adminEmail = config('mail.admin_notification_email') ?: config('mail.from.address');
        $success = true;

        if (!empty($adminEmail)) {
            try {
                Mail::to($adminEmail)->send(new ContactReceivedAdminMail($contact));
                Log::info("Contact form notification sent to admin {$adminEmail} for message ID {$contact->id}.");
            } catch (\Throwable $e) {
                Log::error("Failed sending contact notification to admin ({$adminEmail}): " . $e->getMessage());
                $success = false;
            }
        }

        if (!empty($contact->email) && filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
            try {
                $visitorName = trim($contact->first_name . ' ' . $contact->last_name);
                Mail::to($contact->email, $visitorName ?: null)->send(new ContactConfirmationVisitorMail($contact));
                Log::info("Contact confirmation acknowledgment sent to visitor {$contact->email}.");
            } catch (\Throwable $e) {
                Log::error("Failed sending confirmation acknowledgment to visitor ({$contact->email}): " . $e->getMessage());
            }
        }

        return $success;
    }
}
