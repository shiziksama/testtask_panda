<?php
namespace App\Console;

use App\Models\Url;
use App\Services\OLXScraper;
use Illuminate\Console\Command;

class CheckPricesCommand extends Command {
    protected $signature = 'check:prices';
    protected $description = 'Check prices of subscribed OLX listings and send notifications if they change';

    public function handle() {
        $urls = Url::hasOne(Email::class)->get();


        $scraper = new OLXScraper();

        foreach ($urls as $url) {
            $currentPrice = $scraper->getPrice($url->url);
            if ($currentPrice !== null) {
                
                if ($currentPrice != $url->price) {
                    $url->price=$currentPrice;
                    $url->save();
                    Mail::to($url->email)->send(new PriceChanged($url));
             
                }
            }
        }
    }
}
