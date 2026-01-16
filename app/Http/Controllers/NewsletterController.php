<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $key = 'newsletter:' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many subscription attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        RateLimiter::hit($key, 3600);

        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'] ?? null,
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        if (!$subscriber->wasRecentlyCreated && $subscriber->is_active) {
            return back()->withErrors([
                'email' => 'You are already subscribed!',
            ]);
        }

        if (!$subscriber->is_active) {
            $subscriber->update([
                'is_active' => true,
                'subscribed_at' => now(),
                'unsubscribed_at' => null,
            ]);
        }

        return back();
    }

    public function unsubscribe(Request $request, string $email)
    {
        $subscriber = NewsletterSubscriber::where('email', $email)->first();
        
        if ($subscriber) {
            $subscriber->update([
                'is_active' => false,
                'unsubscribed_at' => now(),
            ]);
        }

        return back()->with('success', 'Successfully unsubscribed.');
    }
}

