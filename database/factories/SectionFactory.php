<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class SectionFactory extends BaseFactory
{
    public function definition()
    {
        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'accession' => $this->faker->words(1, true),
            'artwork_id' => $this->getNumericId(),
            'source_id' => $this->getNumericId(),
            'publication_id' => $this->getNumericId(),
            'content' => $this->faker->paragraphs(5, true),
            'source_updated_at' => $this->faker->dateTime(),
        ];
    }

    public function nullable()
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
            'accession' => null,
            'artwork_id' => null,
            'content' => null,
            'source_updated_at' => null,
        ]);
    }
}
