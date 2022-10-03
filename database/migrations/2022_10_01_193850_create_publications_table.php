<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicationsTable extends Migration
{
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->integer('id')->signed()->primary();
            $table->text('title')->nullable();
            $table->text('site');
            $table->text('alias');
            $table->integer('generic_page_id')->signed()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('publications');
    }
}
