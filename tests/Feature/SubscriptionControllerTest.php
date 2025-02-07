<?php

namespace Tests\Feature;

use App\Models\Email;
use App\Models\Url;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;
use Illuminate\Support\Facades\Log;

class SubscriptionControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_subscribe_with_valid_input()
    {
        //deleting for testing purposes
        Url::where('url', 'https://example.com')->delete();
        Email::where('email', 'test@examle.com')->delete();

        $scraperMock = Mockery::mock(OLXScraper::class);
        $this->app->instance(OLXScraper::class, $scraperMock);
        $scraperMock->shouldReceive('getPrice')
            ->with('https://example.com')
            ->andReturn(100);

        $response = $this->postJson('/subscribe', [
            'url' => 'https://example.com',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Subscribed successfully']);

        $this->assertDatabaseHas('urls', ['url' => 'https://example.com']);
        $this->assertDatabaseHas('emails', ['email' => 'test@example.com']);
        $url = Url::where('url', 'https://example.com')->first();
        $email = Email::where('email', 'test@example.com')->first();
        //$this->assertEquals(100, $url->price);
        $this->assertTrue($url->emails->contains($email));

        Mockery::close();
    }

    public function test_subscribe_with_invalid_email()
    {
        $response = $this->postJson('/subscribe', [
            'url' => 'https://example.com',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Invalid input']);
    }

    public function test_subscribe_with_empty_url()
    {
        $response = $this->postJson('/subscribe', [
            'url' => '',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(400)
                 ->assertJson(['error' => 'Invalid input']);
    }

    public function test_subscribe_with_existing_subscription()
    {
        $url = Url::firstOrCreate(['url' => 'https://example.com']);
        $email = Email::firstOrCreate(['email' => 'test@example.com']);
        $url->emails()->attach($email->id);

        $response = $this->postJson('/subscribe', [
            'url' => 'https://example.com',
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Already subscribed']);
    }
}
