<?php
namespace App\Console;

use App\Models\Url;
use App\Services\OLXScraper;
use Illuminate\Console\Command;
use App\Models\Email;
use Illuminate\Support\Facades\Mail;
use App\Mail\PriceChanged;

class CheckPricesCommand extends Command {
    protected $signature = 'prices:check';
    protected $description = 'Check prices of subscribed OLX listings and send notifications if they change';

    public function handle() {

        $urls = Url::has('emails')->get();

        foreach ($urls as $url) {
            $currentPrice = OLXScraper::getPrice($url->url);
            if ($currentPrice !== null) {
                
                if ($currentPrice != $url->price) {
                    $url->price=$currentPrice;
                    $url->save();
                    foreach($url->emails as $email){
                        Mail::to($email->email)->send(new PriceChanged($url));
                    }
                }
            }
        }
    }
}
