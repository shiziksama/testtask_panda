<?php
namespace Tests\Unit;

use Tests\TestCase;
use App\Services\OLXScraper;
use Illuminate\Support\Facades\Http;

class OLXScraperTest extends TestCase {
    public function testGetPrice() {
        Http::fake([
            'https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html' => Http::response('<script type="application/ld+json">{"offers":{"price":123.45}}</script>', 200)
        ]);

        $scraper = new OLXScraper();
        $price = $scraper->getPrice('https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html');

        $this->assertEquals(123.45, $price);
    }

    public function testGetPriceServerError() {
        Http::fake([
            'https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html' => Http::response(null, 500)
        ]);

        $scraper = new OLXScraper();
        $price = $scraper->getPrice('https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html');

        $this->assertNull($price);
    }

    public function testGetPriceNoJsonLd() {
        Http::fake([
            'https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html' => Http::response('<html><body>No JSON-LD script here</body></html>', 200)
        ]);

        $scraper = new OLXScraper();
        $price = $scraper->getPrice('https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html');

        $this->assertNull($price);
    }

    public function testGetPriceNoOffersOrPrice() {
        Http::fake([
            'https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html' => Http::response('<script type="application/ld+json">{"name":"Test Item"}</script>', 200)
        ]);

        $scraper = new OLXScraper();
        $price = $scraper->getPrice('https://www.olx.ua/d/uk/obyavlenie/test-ID123456.html');

        $this->assertNull($price);
    }
}
