<?php

namespace Tests\Csv;

use Illuminate\Support\Facades\Artisan;

use League\Csv\Reader;
use App\Models\CsvFile;
use Illuminate\Support\Facades\Storage;

use Aic\Hub\Foundation\Testing\FeatureTestCase;

class CsvExportTestCase extends FeatureTestCase
{
    protected $resourceName;

    public function tearDown(): void
    {
        Artisan::call('csv:clear');

        parent::tearDown();
    }

    protected function getCsvReader()
    {
        $csvFile = CsvFile::first();
        $csvPath = Storage::disk('public')->path($csvFile->filename);

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0);

        return $csv;
    }
}
