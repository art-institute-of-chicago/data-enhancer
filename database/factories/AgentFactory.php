<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class AgentFactory extends BaseFactory
{
    public function definition()
    {
        $birthYear = $this->faker->numberBetween(-5000, 2000);
        $deathYear = null;

        if ($birthYear < 1920 || $this->faker->numberBetween(0, 5) < 2) {
            $deathYear = $birthYear + $this->faker->numberBetween(20, 90);
            $deathYear = min($deathYear, 2020);
        }

        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'birth_year' => $birthYear,
            'death_year' => $deathYear,
            'ulan_id' => $this->getValidId(),
            'ulan_certainty' => $this->faker->numberBetween(0, 3),
        ];
    }
}
