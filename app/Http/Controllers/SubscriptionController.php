<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Url;
use App\Services\EmailService;
use Illuminate\Http\Request;

class SubscriptionController {
    public function subscribe(Request $request){
        $urlValue = $request->get('url');
        $emailValue = $request->get('email');

        // Validate input
        if (filter_var($emailValue, FILTER_VALIDATE_EMAIL) === false || empty($urlValue)) {
            return response()->json(['error' => 'Invalid input'],400);
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
            return response()->json(['message' => 'Already subscribed'],200);
        }

        // Create new subscription
        $url->emails()->attach($email->id);

        return response()->json(['message' => 'Subscribed successfully'],200);
    }
}
