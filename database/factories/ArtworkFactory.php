<?php

namespace Database\Factories;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;

class ArtworkFactory extends BaseFactory
{
    public function definition()
    {
        $width = $this->faker->randomNumber(3);
        $height = $this->faker->randomNumber(3);
        $depth = null;

        $dimensionDisplay = $height . ' × ' . $width;

        if ($this->faker->numberBetween(0, 4) < 2) {
            $depth = $this->faker->randomNumber(3);
            $dimensionDisplay .= ' × ' . $depth;
        }

        $dimensionDisplay .= ' mm';

        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'dimension_display' => $dimensionDisplay,
            'width' => $width,
            'height' => $height,
            'depth' => $depth,
            'medium_display' => $this->faker->words(5, true),
            'linked_art_json' => $this->getLinkedArtJson(),
            'source_updated_at' => $this->faker->dateTime(),
        ];
    }

    public function nullable()
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
            'dimension_display' => null,
            'width' => null,
            'height' => null,
            'depth' => null,
            'medium_display' => null,
            'linked_art_json' => null,
            'source_updated_at' => null,
        ]);
    }

    private function getLinkedArtJson()
    {
        return json_decode(<<<END
        {
            "see_also": [
                {
                    "id": "https://vangoghworldwide.org/data/artwork/F1234"
                }
            ]
        }
        END);
    }
}
