<?php
namespace App\Services;

use Symfony\Component\BrowserKit\HttpBrowser;

class OLXScraper {
    public function getPrice(string $url): ?float {
        $client = new HttpBrowser();
        $crawler = $client->request('GET', $url);

        // Extract price from the page
        $priceNode = $crawler->filter('.ad-price-container h3');
        if ($priceNode->count() > 0) {
            $priceText = $priceNode->text();
            return floatval(preg_replace('/[^\d.]/', '', $priceText));
        }

        return null;
    }
}
