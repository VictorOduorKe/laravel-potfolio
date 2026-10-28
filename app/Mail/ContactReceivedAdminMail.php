<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReceivedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Contact $contact
    ) {}

    public function envelope(): Envelope
    {
        $visitorName = trim($this->contact->first_name . ' ' . $this->contact->last_name);

        return new Envelope(
            subject: 'New Portfolio Contact: ' . $visitorName,
            replyTo: [
                new Address($this->contact->email, $visitorName ?: 'Visitor'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-admin',
        );
    }
}
