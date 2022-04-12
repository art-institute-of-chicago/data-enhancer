<?php

namespace Tests\Concerns;

use Tests\Fakes\FakeMigration;
use Tests\Fakes\FakeModel;

trait HasFakeModel
{
    protected $modelClass;

    protected function setUpHasFakeModel()
    {
        FakeMigration::createFakeTable();

        $this->modelClass = FakeModel::class;
    }
}
