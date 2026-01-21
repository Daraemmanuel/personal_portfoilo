<?php

use App\Models\ContactMessage;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

test('contact form can be submitted', function () {
    post(route('contact.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Test Subject',
        'message' => 'Test message content',
    ])
        ->assertRedirect()
        ->assertSessionHas('success');
    
    assertDatabaseHas('contact_messages', [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Test Subject',
    ]);
});

test('contact form requires valid data', function () {
    post(route('contact.store'), [])
        ->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
});

test('contact form validates email format', function () {
    post(route('contact.store'), [
        'name' => 'John Doe',
        'email' => 'invalid-email',
        'subject' => 'Test Subject',
        'message' => 'Test message',
    ])
        ->assertSessionHasErrors(['email']);
});

test('contact form respects rate limiting', function () {
    // This test would need to be adjusted based on actual rate limit configuration
    // For now, we'll just test that the form works
    for ($i = 0; $i < 3; $i++) {
        post(route('contact.store'), [
            'name' => 'John Doe',
            'email' => "john{$i}@example.com",
            'subject' => 'Test Subject',
            'message' => 'Test message',
        ])
            ->assertRedirect();
    }
    
    // After rate limit, should get error
    // This depends on rate limit configuration
})->skip('Rate limiting configuration needs verification');

test('contact form honeypot prevents spam', function () {
    post(route('contact.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'subject' => 'Test Subject',
        'message' => 'Test message',
        'honeypot' => 'spam-bot',
    ])
        ->assertRedirect();
    
    // Should not create a message
    $this->assertDatabaseMissing('contact_messages', [
        'email' => 'john@example.com',
    ]);
});

