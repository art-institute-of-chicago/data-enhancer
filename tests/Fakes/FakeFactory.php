<?php

namespace Tests\Fakes;

use Aic\Hub\Foundation\AbstractFactory;

class FakeFactory extends AbstractFactory
{
    public function definition()
    {
        return [
            'id' => $this->getValidId(),
            'title' => $this->getTitle(),
            'acme_id' => $this->getNumericId(),
            'some_json' => (object) [
                'hello' => 'world',
            ],
        ];
    }

    public function nullable()
    {
        return $this->state(fn (array $attributes) => [
            'title' => null,
            'acme_id' => null,
            'some_json' => null,
        ]);
    }

    public function modelName()
    {
        return FakeModel::class;
    }
}
