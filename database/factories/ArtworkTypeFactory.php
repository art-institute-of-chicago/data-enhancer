<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class ArtworkTypeFactory extends BaseFactory
{
    public function definition()
    {
        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'aat_id' => $this->getNumericId(),
            'source_updated_at' => $this->faker->dateTime(),
        ];
    }

    public function nullable()
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
            'aat_id' => null,
            'source_updated_at' => null,
        ]);
    }
}
