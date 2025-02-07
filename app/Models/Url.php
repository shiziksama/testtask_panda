<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\OLXSCRAPER;

class Url extends Model {
    protected $fillable = ['url'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->price=OLXSCRAPER::getPrice($model->url);
        });
    }

    public function emails() {
        return $this->belongsToMany(Email::class);
    }
}
