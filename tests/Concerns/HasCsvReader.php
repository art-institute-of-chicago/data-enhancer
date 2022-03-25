<?php

namespace Tests\Concerns;

use League\Csv\Reader;
use App\Models\CsvFile;
use Illuminate\Support\Facades\Storage;

trait HasCsvReader
{
    protected function getCsvReader()
    {
        $csvFile = CsvFile::first();
        $csvPath = Storage::disk('public')->path($csvFile->filename);

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }
}
