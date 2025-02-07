<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Url;
use App\Services\EmailService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubscriptionController {
    public function subscribe(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $urlValue = $data['url'];
        $emailValue = $data['email'];

        // Validate input
        if (filter_var($emailValue, FILTER_VALIDATE_EMAIL) === false || empty($urlValue)) {
            return $response->withStatus(400)->withJson(['error' => 'Invalid input']);
        }

        // Check if URL exists
        $url = Url::where('url', $urlValue)->first();
        if (!$url) {
            $url = new Url();
            $url->url = $urlValue;
            $url->save();
        }

        // Check if Email exists
        $email = Email::where('email', $emailValue)->first();
        if (!$email) {
            $email = new Email();
            $email->email = $emailValue;
            $email->save();
        }

        // Check if subscription already exists for the given URL and email
        if ($url->emails()->where('email_id', $email->id)->exists()) {
            return $response->withStatus(200)->withJson(['message' => 'Already subscribed']);
        }

        // Create new subscription
        $url->emails()->attach($email->id);

        return $response->withStatus(201)->withJson(['message' => 'Subscribed successfully']);
    }
}
