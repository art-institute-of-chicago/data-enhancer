<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Publication extends BaseModel
{
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'site' => 'string',
        'alias' => 'string',
        'generic_page_id' => 'integer',
        'updated_at' => 'datetime',
    ];
}
