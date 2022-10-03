<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class PublicationFactory extends BaseFactory
{
    public function definition()
    {
        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'site' => $this->faker->word(),
            'alias' => $this->faker->word(),
            'generic_page_id' => $this->getNumericId(),
        ];
    }

    public function nullable()
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
            'generic_page_id' => null,
        ]);
    }
}
