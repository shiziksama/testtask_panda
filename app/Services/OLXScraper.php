<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class OLXScraper {
    public function getPrice(string $url): ?float {
        $response = Http::get($url);
        if (!$response->successful()) {
            return null;
        }
        
        $crawler = new Crawler($response->body());
    
        $jsonLd = $crawler->filter('script[type="application/ld+json"]')->first();
        if ($jsonLd->count() > 0) {
            $jsonData = json_decode($jsonLd->first()->text(), true);
            if (isset($jsonData['offers']['price'])) {
                return floatval($jsonData['offers']['price']);
            }
        }

        return null;
    }
}
