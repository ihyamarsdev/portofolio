<?php

namespace App\Notifications;

use App\Mail\InboxMessageMail;
use App\Models\Inbox;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InboxMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Inbox $inbox
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Contact: {$this->inbox->subject}")
            ->greeting("New message from {$this->inbox->name}")
            ->line("**From:** {$this->inbox->name} ({$this->inbox->email})")
            ->line("**Subject:** {$this->inbox->subject}")
            ->line("**Message:**")
            ->line($this->inbox->message)
            ->action('View in Dashboard', url('/admin/inboxes'))
            ->line("Received: {$this->inbox->created_at->format('M d, Y H:i')}");
    }
}