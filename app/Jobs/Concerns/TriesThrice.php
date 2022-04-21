<?php

namespace App\Jobs\Concerns;

trait TriesThrice
{
    public $tries = 3;

    public function backoff()
    {
        return [
            random_int(1, 2),
            random_int(3, 7),
            random_int(8, 12),
        ];
    }
}
