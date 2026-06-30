<?php
namespace App\Services;

use App\Models\Subscriber;

class NewsletterService
{
    public static function subscribe(string $email, string $name = ''): array
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Please enter a valid email address.'];
        }

        if (Subscriber::subscribe($email, $name)) {
            return ['success' => true, 'message' => 'Thank you for subscribing! Check your inbox for confirmation.'];
        }

        return ['success' => false, 'message' => 'This email is already subscribed.'];
    }

    public static function unsubscribe(string $token): bool
    {
        return Subscriber::unsubscribe($token);
    }
}
