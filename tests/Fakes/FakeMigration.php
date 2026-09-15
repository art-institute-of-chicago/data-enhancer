<?php

namespace Tests\Fakes;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class FakeMigration
{
    public static function createFakeTable()
    {
        if (Schema::hasTable('foos')) {
            return;
        }

        Schema::create('foos', function (Blueprint $table) {
            $table->integer('id', true, true);
            $table->text('title')->nullable();
            $table->integer('acme_id')->nullable();
            $table->json('some_json')->nullable();
            $table->timestamps();
        });
    }
}
