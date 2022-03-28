<?php

namespace App\Jobs;

use Carbon\Carbon;
use League\Csv\Writer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\CsvFile;

class ExportCsv extends AbstractJob
{
    public function __construct(
        private string $resourceName,
        private ?array $ids,
        private ?string $since,
        private ?array $blankFields,
        private ?array $exportFields,
    ) {
    }

    public function tags()
    {
        return [
            'source:csv',
            'resource:' . $this->resourceName,
        ];
    }

    public function handle()
    {
        $this->debug(sprintf(
            'CSV %s, %s, %s, %s, %s',
            $this->resourceName,
            json_encode($this->ids),
            $this->since,
            json_encode($this->blankFields),
            json_encode($this->exportFields),
        ));

        $resourceConfig = config('aic.output.csv.resources.' . $this->resourceName);

        if (!$resourceConfig) {
            throw InvalidArgumentException('Undefined resource: ' . $this->resourceName);
        }

        $modelClass = $resourceConfig['model'];
        $transformerClass = $resourceConfig['transformer'];

        $model = ($modelClass)::instance();
        $transformer = new $transformerClass();

        $primaryKey = $model->getKeyName();
        $query = $modelClass::query();

        $query->orderBy($primaryKey);

        if (!empty($this->ids)) {
            $query->whereIn($primaryKey, $this->ids);
        } else {
            $this->ids = null;
        }

        if (!empty($this->since)) {
            $query->whereDate('updated_at', '>=', Carbon::parse($this->since));
        } else {
            $this->since = null;
        }

        if (!empty($this->blankFields)) {
            $query->where(function ($query) use ($transformer) {
                $blankColumns = $transformer->getRequiredFields($this->blankFields);

                $query->whereNull(array_shift($blankColumns));

                foreach ($blankColumns as $blankColumn) {
                    $query->orWhereNull($blankColumn);
                }
            });
        } else {
            $this->blankFields = null;
        }

        if (!empty($this->exportFields)) {
            if (!in_array($primaryKey, $this->exportFields)) {
                array_unshift($this->exportFields, $primaryKey);
            }

            $exportColumns = $transformer->getRequiredFields($this->exportFields);
            $query->select($exportColumns);
        } else {
            $this->exportFields = null;
        }

        $count = $query->count();

        do {
            $csvId = Str::random(6);
        } while (CsvFile::where('id', $csvId)->exists());

        $csvFilename = sprintf('%s-%s-%s.csv',
            Carbon::now()->format('Y-m-d'),
            $this->resourceName,
            $csvId
        );

        Storage::disk('public')->put($csvFilename, null);

        $csvPath = Storage::disk('public')->path($csvFilename);

        $writer = Writer::createFromPath($csvPath, 'w+');
        $writer->insertOne($this->exportFields ?? $transformer->getFieldNames());

        foreach ($query->cursor() as $datum) {
            $transformedDatum = $transformer->transform($datum, $this->exportFields);
            $writer->insertOne($transformedDatum);
        }

        CsvFile::create([
            'id' => $csvId,
            'resource' => $this->resourceName,
            'filename' => $csvFilename,
            'count' => $count,
            'since' => $this->since,
            'ids' => $this->ids,
            'blank_fields' => $this->blankFields,
            'export_fields' => $this->exportFields,
        ]);
    }
}
