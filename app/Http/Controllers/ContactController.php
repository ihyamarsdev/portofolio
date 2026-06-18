<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInboxRequest;
use App\Mail\ContactMessageReceived;
use App\Models\Inbox;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(StoreInboxRequest $request)
    {
        $validated = $request->validated();

        $inbox = Inbox::create($validated);

        try {
            Mail::to('ihyamars@gmail.com')->send(new ContactMessageReceived($inbox));
        } catch (\Throwable $exception) {
            // Log the error but don't crash the user request if mail sending fails
            report($exception);
        }

        return back()->with('success', 'Thank you for your message! I\'ll get back to you soon.');
    }
}
