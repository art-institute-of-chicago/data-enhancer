<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtworkTypesTable extends Migration
{
    public function up()
    {
        Schema::create('artwork_types', function (Blueprint $table) {
            $table->integer('id')->signed()->primary();
            $table->text('title')->nullable();
            $table->integer('aat_id')->signed()->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artwork_types');
    }
}
