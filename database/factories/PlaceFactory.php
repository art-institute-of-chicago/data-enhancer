<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class PlaceFactory extends BaseFactory
{
    public function definition()
    {
        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'tgn_id' => $this->getNumericId(),
            'source_updated_at' => $this->faker->dateTime(),
        ];
    }

    public function nullable()
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
            'latitude' => null,
            'longitude' => null,
            'tgn_id' => null,
            'source_updated_at' => null,
        ]);
    }
}
