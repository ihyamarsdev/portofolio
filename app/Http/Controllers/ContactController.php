<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInboxRequest;
use App\Models\Inbox;

class ContactController extends Controller
{
    public function store(StoreInboxRequest $request)
    {
        $validated = $request->validated();

        Inbox::create($validated);

        return back()->with('success', 'Thank you for your message! I\'ll get back to you soon.');
    }
}
