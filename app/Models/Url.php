<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\OLXScraper;

class Url extends Model {
    use HasFactory;
    protected $fillable = ['url'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $scraper = app(OLXScraper::class);
            $model->price = $scraper->getPrice($model->url);
        });
    }

    public function emails() {
        return $this->belongsToMany(Email::class);
    }
}
