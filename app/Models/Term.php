<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Term extends BaseModel
{
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'aat_id' => 'integer',
    ];
}
