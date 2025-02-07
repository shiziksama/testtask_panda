<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Url;
use App\Models\Email;
use App\Services\OLXScraper;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriceChanged;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery;

class CheckPricesCommandTest extends TestCase
{
    use DatabaseTransactions;

    public function testHandle()
    {
        Mail::fake();
        Url::query()->delete();//для тесту необхідно видалити всі записи з таблиці urls. вони будуть відновлені за допомогою databaseTransactions


        $url = Event::fakeFor(function () {
            return Url::factory()->create(['price' => 100]);
        });
        $email = Email::factory()->create();
        $url->emails()->attach($email->id);

        $scraperMock = Mockery::mock(OLXScraper::class);
        $this->app->instance(OLXScraper::class, $scraperMock);

        $scraperMock->shouldReceive('getPrice')
            ->with($url->url)
            ->andReturn(150);

                $this->artisan('prices:check')
            ->assertExitCode(0);

        $this->assertDatabaseHas('urls', [
            'id' => $url->id,
            'price' => 150,
        ]);

        Mail::assertSent(PriceChanged::class, function ($mail) use ($email) {
            return $mail->hasTo($email->email);
        });

        Mockery::close();
    }
}
