<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function store(StoreContactRequest $request)
    {
        // Rate limiting: More lenient in development, stricter in production
        $maxAttempts = app()->environment('local') ? 20 : 5;
        $decaySeconds = app()->environment('local') ? 60 : 3600; // 1 minute in dev, 1 hour in production
        $key = 'contact:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            throw ValidationException::withMessages([
                'email' => "Too many messages sent. Please try again in {$minutes} minute(s).",
            ]);
        }

        RateLimiter::hit($key, $decaySeconds);

        $validated = $request->validated();

        // Check honeypot
        if (!empty($request->input('honeypot'))) {
            // Silently ignore spam attempts
            return back();
        }

        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // Send email notification via queue
        \App\Jobs\SendContactEmailNotification::dispatch($contactMessage);

        return back()->with('success', 'Thank you for your message! I\'ll get back to you soon.');
    }
}
