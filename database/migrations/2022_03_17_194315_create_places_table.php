<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacesTable extends Migration
{
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->text('title')->nullable();
            $table->double('latitude', 16, 13)->nullable();
            $table->double('longitude', 16, 13)->nullable();
            $table->integer('tgn_id')->signed()->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('places');
    }
}
