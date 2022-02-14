<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Agent extends BaseModel
{
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'birth_year' => 'integer',
        'death_year' => 'integer',
        'ulan_id' => 'integer',
        'ulan_certainty' => 'integer',
        'source_updated_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
