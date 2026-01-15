<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactController extends Controller
{
    public function store(Request $request)
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
            'honeypot' => 'nullable|string|max:0', // Honeypot field
        ]);

        // Check honeypot
        if (!empty($request->input('honeypot'))) {
            return response()->json(['message' => 'Message sent successfully'], 200);
        }

        $contactMessage = ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
        ]);

        // Send email notification
        try {
            Mail::raw(
                "Name: {$contactMessage->name}\nEmail: {$contactMessage->email}\nSubject: {$contactMessage->subject}\n\nMessage:\n{$contactMessage->message}",
                function ($message) use ($contactMessage) {
                    $message->to(config('mail.from.address'))
                        ->subject('New Contact Form Message: ' . $contactMessage->subject)
                        ->replyTo($contactMessage->email, $contactMessage->name);
                }
            );
        } catch (\Exception $e) {
            // Log error but don't fail the request
            \Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return back()->with('success', 'Thank you for your message! I\'ll get back to you soon.');
    }
}
