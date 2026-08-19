<?php

namespace App\Mail;

use App\Models\Inbox;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InboxMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Inbox $inbox
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Contact: {$this->inbox->subject}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.inbox-message',
            with: [
                'name' => $this->inbox->name,
                'email' => $this->inbox->email,
                'subject' => $this->inbox->subject,
                'message' => $this->inbox->message,
                'date' => $this->inbox->created_at->format('M d, Y H:i'),
            ],
        );
    }
}
