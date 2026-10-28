<?php

namespace App\Mail;

use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetUrl;

    public function __construct(
        public Admin $admin,
        public string $token,
        public int $expiresInMinutes = 60
    ) {
        $canonicalBase = config('app.canonical_url', 'https://thetechcraft.co.ke');
        $this->resetUrl = rtrim((string) $canonicalBase, '/') . '/admin/reset-password/' . $token . '?email=' . urlencode($admin->email);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'TheTechCraft Admin: Password Reset Request',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-reset-password',
            with: [
                'resetUrl' => $this->resetUrl,
                'admin' => $this->admin,
                'expiresInMinutes' => $this->expiresInMinutes,
            ],
        );
    }
}
