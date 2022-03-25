<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\CsvFile;
use Illuminate\Support\Facades\Storage;

class CsvClear extends AbstractCommand
{
    protected $signature = 'csv:clear {--before=}';

    protected $description = 'Delete exported CSV files';

    public function handle()
    {
        $before = $this->option('before')
            ? Carbon::parse($this->option('before'))
            : null;

        foreach (CsvFile::cursor() as $csvFile) {
            if ($before && $csvFile->updated_at->gt($before)) {
                continue;
            }

            Storage::disk('public')->delete($csvFile->filename);
            $csvFile->delete();
        }
    }
}
