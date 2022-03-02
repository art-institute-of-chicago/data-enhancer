<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsTable extends Migration
{
    public function up()
    {
        Schema::create('terms', function (Blueprint $table) {
            $table->string('id')->signed()->primary();
            $table->text('title');
            $table->integer('aat_id')->signed()->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('terms');
    }
}
