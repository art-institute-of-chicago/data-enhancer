<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class ArtworkFactory extends BaseFactory
{
    public function definition()
    {
        $width = $this->faker->randomFloat(1, 0, 200);
        $height = $this->faker->randomFloat(1, 0, 200);
        $depth = null;

        $dimensionDisplay = $height . ' × ' . $width;

        if ($this->faker->numberBetween(0, 4) < 2) {
            $depth = $this->faker->randomFloat(1, 0, 200);
            $dimensionDisplay .= ' × ' . $depth;
        }

        $dimensionDisplay .= ' cm';

        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'dimension_display' => $dimensionDisplay,
            'width' => $width,
            'height' => $height,
            'depth' => $depth,
            'medium_display' => $this->faker->words(5, true),
            'support_aat_id' => $this->getNumericId(),
            'source_updated_at' => $this->faker->dateTime(),
        ];
    }
}
