<?php

namespace App\Transformers\Imports;

class AgentTransformer extends AbstractTransformer
{
    protected static $requiredFields = [
        'id' => 'integer',
        'sort_title' => 'string',
        'birth_date' => 'integer|null',
        'death_date' => 'integer|null',
    ];
}
