<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommissionEnquiryMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public readonly array $data) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Commission Enquiry: ' . $this->data['commission_type'] . ' — ' . $this->data['subject'],
            replyTo: new Address($this->data['email'], $this->data['name']),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.commission',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
