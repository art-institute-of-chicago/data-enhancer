<?php

namespace App\Transformers\Imports;

class TermTransformer extends AbstractTransformer
{
    protected static $requiredFields = [
        'id' => 'integer',
        'title' => 'string',
    ];
}
