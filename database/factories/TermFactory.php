<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class TermFactory extends BaseFactory
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

    public function getValidId()
    {
        return 'TM-' . $this->getNumericId();
    }
}
