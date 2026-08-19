<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInboxRequest;
use App\Models\Inbox;
use App\Notifications\InboxMessageNotification;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    public function store(StoreInboxRequest $request)
    {
        $inbox = Inbox::create($request->validated());

        // Dispatch queued email notification to admin
        Notification::route('mail', config('mail.admin_address'))
            ->notify(new InboxMessageNotification($inbox));

        return back()->with('success', 'Thank you for your message! I\'ll get back to you soon.');
    }
}
