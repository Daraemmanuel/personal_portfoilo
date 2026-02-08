<?php

namespace App\Jobs;

use App\Models\ContactMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendContactEmailNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContactMessage $contactMessage
    ) {
        // Set queue name if needed
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::raw(
                "Name: {$this->contactMessage->name}\nEmail: {$this->contactMessage->email}\nSubject: {$this->contactMessage->subject}\n\nMessage:\n{$this->contactMessage->message}",
                function ($message) {
                    $adminEmail = config('portfolio.admin_email') ?? 'immanuelatwork@gmail.com';
                    
                    $message->to($adminEmail)
                        ->from(config('mail.from.address'), config('app.name'))
                        ->subject('New Contact Form Message: ' . $this->contactMessage->subject)
                        ->replyTo($this->contactMessage->email, $this->contactMessage->name);
                }
            );
        } catch (\Exception $e) {
            // Log error and re-throw to trigger retry mechanism
            Log::error('Failed to send contact email notification', [
                'error' => $e->getMessage(),
                'contact_message_id' => $this->contactMessage->id,
            ]);
            
            // Re-throw to allow queue retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Contact email notification job failed permanently', [
            'error' => $exception->getMessage(),
            'contact_message_id' => $this->contactMessage->id,
        ]);
    }
}

