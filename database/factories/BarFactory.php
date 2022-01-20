<?php

namespace Database\Factories;

use App\Bar;
use App\Foo;
use Illuminate\Database\Eloquent\Factories\Factory;

class BarFactory extends Factory
{
    protected $model = Bar::class;

    public function definition()
    {
        return [
            'id' => $faker->unique()->randomNumber(6),
            'title' => ucfirst($faker->words(3, true)),
            'foo_id' => Foo::all()->get()->random(),
        ];
    }
}
