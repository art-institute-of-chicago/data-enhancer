<?php

namespace App\Console\Commands;

use App\Jobs\ImportSource;
use Aic\Hub\Foundation\Console\Concerns\HasSince;

class ImportAggregator extends AbstractCommand
{
    use HasSince;

    protected $signature = 'import:aggregator {resource?} {id?} {--max-pages=}';

    protected $description = 'Import configured resources from the aggregator';

    public function handle()
    {
        ImportSource::dispatch(
            'aggregator',
            $this->argument('resource'),
            $this->argument('id'),
            $this->option('full'),
            $this->option('since'),
            $this->option('max-pages'),
        );
    }
}
