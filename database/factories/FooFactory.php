<?php

namespace Database\Factories;

use App\Foo;
use Illuminate\Database\Eloquent\Factories\Factory;

class FooFactory extends Factory
{
    protected $model = Foo::class;

    public function definition()
    {
        return [
            'id' => $faker->unique()->randomNumber(6),
            'title' => ucfirst($faker->words(3, true)),
        ];
    }
}
