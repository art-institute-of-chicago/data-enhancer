<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Agent extends BaseModel
{
    protected $dates = [
        'source_updated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'birth_year' => 'integer',
        'death_year' => 'integer',
        'ulan_id' => 'integer',
        'ulan_certainty' => 'integer',
        'ulan_xml' => 'string',
        'source_updated_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
