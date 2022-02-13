<?php

namespace App\Console\Commands;

use App\Jobs\ImportSource;
use Aic\Hub\Foundation\Console\Concerns\HasSince;

class ImportAggregator extends AbstractCommand
{
    use HasSince;

    protected $signature = 'import:aggregator {resource?}';

    protected $description = 'Import configured resources from the aggregator';

    public function handle()
    {
        ImportSource::dispatch(
            'aggregator',
            $this->argument('resource'),
            $this->option('full'),
            $this->option('since'),
        );
    }
}
