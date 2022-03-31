<?php

namespace Tests\Concerns;

use Carbon\Carbon;

trait HasDates
{
    protected function setUpHasDates(): void
    {
        $this->travelTo(Carbon::now());
    }

    protected function tearDownHasDates(): void
    {
        $this->travelBack();
    }
}
