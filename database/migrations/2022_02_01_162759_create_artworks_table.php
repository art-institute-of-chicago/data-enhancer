<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtworksTable extends Migration
{
    public function up()
    {
        Schema::create('artworks', function (Blueprint $table) {
            $table->integer('id')->signed()->primary();
            $table->text('title')->nullable();
            $table->text('dimension_display')->nullable();
            $table->integer('width')->signed()->nullable();
            $table->integer('height')->signed()->nullable();
            $table->integer('depth')->signed()->nullable();
            $table->text('medium_display')->nullable();
            $table->integer('support_aat_id')->signed()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('artworks');
    }
}
