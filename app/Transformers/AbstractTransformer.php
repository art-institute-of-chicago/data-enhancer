<?php

namespace App\Transformers;

use Closure;

abstract class AbstractTransformer
{
    private $mappedFields;

    abstract protected function getFields();

    final public function transform($datum, $requestedFields = null): array
    {
        $datum = $this->getDatum($datum);
        $mappedFields = $this->getMappedFields();

        if (!empty($requestedFields)) {
            $mappedFields = array_intersect_key(
                $mappedFields,
                array_flip($requestedFields)
            );
        }

        return array_map(function ($mappedField) use ($datum) {
            return call_user_func($mappedField['value'], $datum);
        }, $mappedFields);
    }

    /**
     * What fields are required from the input to make this work?
     */
    public function getRequiredFields($requestedFields = null): array
    {
        return collect($this->getMappedFields())
            ->map(function ($mappedField) {
                return $mappedField['requires'];
            })
            ->only($requestedFields)
            ->flatten()
            ->unique()
            ->values()
            ->all();
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
            if ($fieldMapping instanceof Closure) {
                $mappedFields[$fieldName] = [
                    'value' => $fieldMapping,
                ];
            }
        }

        foreach ($mappedFields as $fieldName => $fieldMapping) {
            if (empty($fieldMapping['value'])) {
                $mappedFields[$fieldName]['value'] = fn (Datum $datum) => $datum->{$fieldName};
            }
        }

        foreach ($mappedFields as $fieldName => $fieldMapping) {
            if (empty($fieldMapping['requires'])) {
                $mappedFields[$fieldName]['requires'] = [
                    $fieldName,
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