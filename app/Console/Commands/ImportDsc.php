<?php

namespace App\Console\Commands;

use App\Jobs\ImportSource;
use Aic\Hub\Foundation\Console\Concerns\HasSince;

class ImportDsc extends AbstractCommand
{
    use HasSince;

    protected $signature = 'import:dsc {resource?} {id?} {--max-pages=}';

    protected $description = 'Import configured resources from the catalogues data service';

    public function handle()
    {
        ImportSource::dispatch(
            'dsc',
            $this->argument('resource'),
            $this->argument('id'),
            $this->option('full'),
            $this->option('since'),
            $this->option('max-pages'),
        );
    }
}
