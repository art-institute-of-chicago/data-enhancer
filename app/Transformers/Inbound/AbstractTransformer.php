<?php

namespace App\Transformers\Inbound;

use Closure;
use App\Transformers\Datum;
use App\Transformers\Inbound\Concerns\HasDates;

abstract class AbstractTransformer
{
    use HasDates;

    private $mappedFields;

    abstract protected function getFields();

    final public function transform($datum): array
    {
        $datum = $this->getDatum($datum);
        $mappedFields = $this->getMappedFields();

        return array_map(function ($mappedField) use ($datum) {
            return call_user_func($mappedField['value'], $datum);
        }, $mappedFields);
    }

    public function getRequiredFields($withRules = false): array
    {
        return $withRules ? $this->requiredFields : array_keys($this->requiredFields);
    }

    private function getMappedFields()
    {
        return $this->mappedFields ?? $this->mappedFields = $this->initMappedFields();
    }

    private function initMappedFields()
    {
        $mappedFields = array_merge(
            $this->getFields(),
            $this->getTraitFields(),
        );

        foreach ($mappedFields as $fieldName => $fieldMapping) {
            if (is_null($fieldMapping)) {
                $mappedFields[$fieldName] = fn (Datum $datum) => $datum->{$fieldName};
            }
        }

        foreach ($mappedFields as $fieldName => $fieldMapping) {
            if ($fieldMapping instanceof Closure) {
                $mappedFields[$fieldName] = [
                    'value' => $fieldMapping,
                ];
            }
        }

        return $mappedFields;
    }

    private function getTraitFields()
    {
        $fields = [];

        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'getFieldsFor' . class_basename($trait))) {
                $fields = array_merge($fields, $this->{$method}());
            }
        }

        return $fields;
    }

    private function getDatum($datum): Datum
    {
        return $datum instanceof Datum ? $datum : new Datum($datum);
    }
}
