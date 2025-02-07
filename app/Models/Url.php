<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model {
    protected $fillable = ['url'];

    public function emails() {
        return $this->belongsToMany(Email::class);
    }
}
