<?php

namespace App\Notifications;

use App\Mail\AdminResetPasswordMail;
use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AdminResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): AdminResetPasswordMail
    {
        $admin = $notifiable instanceof Admin ? $notifiable : Admin::where('email', $notifiable->getEmailForPasswordReset())->firstOrFail();
        $expires = (int) config('auth.passwords.admins.expire', 60);

        return (new AdminResetPasswordMail($admin, $this->token, $expires))
            ->to($notifiable->getEmailForPasswordReset());
    }
}
