<?php

namespace Database\Factories;

use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

class UrlFactory extends Factory
{
    protected $model = Url::class;

    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'price' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
