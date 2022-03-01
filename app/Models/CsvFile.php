<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsvFile extends Model
{
    protected $casts = [
        'id' => 'integer',
        'resource' => 'string',
        'filename' => 'string',
        'count' => 'integer',
        'since' => 'string',
        'ids' => 'array',
        'blank_fields' => 'array',
        'export_fields' => 'array',
    ];

    protected $guarded = [];
}
