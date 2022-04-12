<?php

namespace Database\Factories;

use App\Enums\TermType;
use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class TermFactory extends BaseFactory
{
    public function definition()
    {
        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'subtype' => TermType::random(),
            'aat_id' => $this->getNumericId(),
            'source_updated_at' => $this->faker->dateTime(),
        ];
    }

    public function nullable()
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
            'subtype' => null,
            'aat_id' => null,
            'source_updated_at' => null,
        ]);
    }

    public function getValidId()
    {
        return 'TM-' . $this->getNumericId();
    }
}
