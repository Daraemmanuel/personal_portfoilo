<?php

use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can subscribe to newsletter', function () {
    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'test@example.com',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('newsletter_subscribers', [
        'email' => 'test@example.com',
    ]);
});

test('cannot subscribe with invalid email', function () {
    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'invalid-email',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertDatabaseMissing('newsletter_subscribers', [
        'email' => 'invalid-email',
    ]);
});

test('cannot subscribe with duplicate email', function () {
    NewsletterSubscriber::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'existing@example.com',
    ]);

    $response->assertSessionHasErrors('email');
});

test('user can unsubscribe from newsletter', function () {
    $subscriber = NewsletterSubscriber::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = $this->post(route('newsletter.unsubscribe', $subscriber->email));

    $response->assertRedirect();
    $this->assertDatabaseHas('newsletter_subscribers', [
        'id' => $subscriber->id,
        'is_active' => false,
    ]);
});

test('newsletter subscription is rate limited', function () {
    // Simulate multiple rapid requests
    for ($i = 0; $i < 10; $i++) {
        $this->post(route('newsletter.subscribe'), [
            'email' => "test{$i}@example.com",
        ]);
    }

    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'test11@example.com',
    ]);

    // Should be rate limited after 10 requests
    $response->assertStatus(429);
});

