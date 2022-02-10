<?php

namespace App\Models;

use Aic\Hub\Foundation\AbstractModel as BaseModel;

class Term extends BaseModel
{
    protected $casts = [
        'id' => 'string',
        'title' => 'string',
        'aat_id' => 'integer',
    ];

    public static function validateId($id)
    {
        return is_string($id)
            && in_array(substr($id, 0, 2), ['TM', 'PC'])
            && is_numeric(substr($id, 3));
    }
}
