<?php

namespace Tests\Concerns;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Aic\Hub\Foundation\AbstractFactory as BaseFactory;
use Aic\Hub\Foundation\AbstractModel as BaseModel;

trait HasFakeModel
{
    protected $modelClass;

    protected function setUpHasFakeModel()
    {
        $this->createFakeTable();

        $modelClass = $this->getFakeModel();
        $factoryClass = $this->getFakeFactory();

        // https://stackoverflow.com/a/49038436
        ($modelClass)::$factoryClass = $factoryClass;
        ($factoryClass)::$modelClass = $modelClass;

        $this->modelClass = $modelClass;
    }

    private function createFakeTable()
    {
        Schema::create('foos', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->text('title')->nullable();
            $table->integer('acme_id')->nullable();
            $table->timestamps();
        });
    }

    private function getFakeModel(): BaseModel
    {
        return new class() extends BaseModel {
            protected $table = 'foos';

            protected $casts = [
                'id' => 'integer',
                'title' => 'string',
                'acme_id' => 'integer',
                'updated_at' => 'datetime',
            ];

            public static $factoryClass;

            protected static function newFactory()
            {
                return (static::$factoryClass)::new();
            }
        };
    }

    private function getFakeFactory(): BaseFactory
    {
        return new class() extends BaseFactory {
            public function definition()
            {
                return [
                    'id' => $this->getValidId(),
                    'title' => $this->getTitle(),
                    'acme_id' => $this->getNumericId(),
                ];
            }

            public function nullable()
            {
                return $this->state(fn (array $attributes) => [
                    'title' => null,
                    'acme_id' => null,
                ]);
            }

            public static $modelClass;

            public function modelName()
            {
                return static::$modelClass;
            }
        };
    }
}
