<?php

namespace App\Transformers\Imports\Concerns;

use Carbon\Carbon;

trait HasDates
{
    /**
     * References a field after transformation, not before.
     *
     * This value is used in two very important contexts:
     *
     * 1. Updates to this field don't count towards determining
     *    whether or not the record was updated in the source.
     *
     * 2. For partial imports, we check this field to see if the
     *    record was updated on or after the `since` date. If not,
     *    we ignore it.
     */
    protected static $sourceUpdatedAtField = 'source_updated_at';

    public static function getSourceUpdatedAtField()
    {
        return self::$sourceUpdatedAtField;
    }

    protected function getDateTime($value)
    {
        return Carbon::parse($value);
    }
}
