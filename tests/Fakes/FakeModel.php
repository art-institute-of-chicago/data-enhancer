<?php

namespace Tests\Fakes;

use Aic\Hub\Foundation\AbstractModel;

class FakeModel extends AbstractModel
{
    protected $table = 'foos';

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'acme_id' => 'integer',
        'some_json' => 'object',
        'updated_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return FakeFactory::new();
    }
}
