<?php

namespace App\Transformers\Imports;

class TermTransformer extends AbstractTransformer
{
    protected $requiredFields = [
        'id' => 'string',
        'title' => 'string',
    ];

    public function getFields()
    {
        return [
            'id' => null,
            'title' => null,
        ];
    }
}
