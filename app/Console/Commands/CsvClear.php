<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\CsvFile;
use Illuminate\Support\Facades\Storage;

class CsvClear extends AbstractCommand
{
    protected $signature = 'csv:clear';

    protected $description = 'Delete old CSV files';

    public function handle()
    {
        foreach (CsvFile::cursor() as $csvFile) {
            if ($csvFile->updated_at->diffInHours(Carbon::now()) > 72) {
                Storage::disk('public')->delete($csvFile->filename);
                $csvFile->delete();
            }
        }
    }
}
