<?php

namespace App\Transformers;

use stdClass;
use JsonSerializable;
use InvalidArgumentException;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Datum implements JsonSerializable
{
    private $datum;

    private $subdatums = [];

    public function __construct($datum)
    {
        if (is_object($datum)) {
            if (!((
                get_class($datum) === stdClass::class
            ) || (
                is_subclass_of($datum, Model::class)
            ))) {
                throw new InvalidArgumentException('attempting to create datum from invalid class');
            }
        } elseif (is_array($datum)) {
            if (!Arr::isAssoc($datum)) {
                throw new InvalidArgumentException('cannot create datum from indexed array');
            }

            $datum = (object) $datum;
        } else {
            throw new InvalidArgumentException('cannot create datum from ' . gettype($datum));
        }

        $this->datum = $datum;
    }

    public function __get($field)
    {
        $value = $this->datum->{$field} ?? null;

        return $this->getCleanValue($value);
    }

    /**
     * Dual-purpose convenience method to force-return data as an array.
     * If `$fields` is omitted, it exposes all data stored in the `datum` property.
     * If `$fields` is defined, it returns the field as an array, even if it's null.
     *
     * @return array
     */
    public function all($field = null)
    {
        if (!isset($field)) {
            $datum = (array) $this->datum;

            return array_map([$this, 'getCleanValue'], $datum);
        }

        // Note how we're getting __get() to fire here
        return $this->{$field} ?? [];
    }

    /**
     * Exposes the `datum` property when serialized into JSON.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return $this->all();
    }

    /**
     * Returns an object or array wrapped in a Datum.
     *
     * @link http://php.net/manual/en/function.spl-object-hash.php
     * @link https://stackoverflow.com/questions/5097632
     *
     * @return \App\Transformers\Datum;
     */
    private function getSubDatum($value)
    {
        if (is_object($value)) {
            $hash = spl_object_hash($value);
        }

        if (is_array($value)) {
            $hash = md5(serialize($value));
        }

        if (!isset($this->subdatums[$hash])) {
            $this->subdatums[$hash] = new Datum($value);
        }

        return $this->subdatums[$hash];
    }

    /**
     * A place to standardize values, e.g. return null instead of empty strings.
     *
     * @return mixed;
     */
    private function getCleanValue($value)
    {
        if (!isset($value)) {
            return null;
        }

        if (is_string($value)) {
            // Standardize on \n newlines
            $value = str_replace(["\r\n", "\r"], "\n", $value);

            // If it's a string, trim before returning
            $value = trim($value);

            // If it's an empty string, return null
            return empty($value) ? null : $value;
        }

        if ($value instanceof Collection) {
            $value = $value->all();
        }

        if (is_array($value)) {
            if (Arr::isAssoc($value)) {
                return $this->getSubDatum($value);
            }

            return array_values(array_filter(array_map([$this, 'getCleanValue'], $value)));
        }

        if ($value instanceof Carbon) {
            return $value;
        }

        if (is_object($value)) {
            if (enum_exists(get_class($value))) {
                return $value;
            }

            return $this->getSubDatum($value);
        }

        return $value;
    }
}
