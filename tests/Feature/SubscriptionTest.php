<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Subscription;

class SubscriptionTest extends TestCase {
    public function testSubscription() {
        $response = $this->post('/subscribe', [
            'url' => 'https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html',
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('emails', [
            'email' => 'test@example.com'
        ]);
        $this->assertDatabaseHas('urls', [
            'url' => 'https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html'
        ]);
        /*
        $this->assertDatabaseHas('email_url', [
            'email_id' =>
            'url_id' => 1
        ]);*/
    }
}
