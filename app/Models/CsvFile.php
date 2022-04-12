<?php

namespace App\Models;

use Aic\Hub\Foundation\Models\Concerns\HasByLastModScope;
use Illuminate\Database\Eloquent\Model;

class CsvFile extends Model
{
    use HasByLastModScope;

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

    public function getCsvUrl()
    {
        return url('/storage/' . $this->filename);
    }
}
