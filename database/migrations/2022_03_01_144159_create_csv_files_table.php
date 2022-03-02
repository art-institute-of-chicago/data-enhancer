<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvFilesTable extends Migration
{
    public function up()
    {
        Schema::create('csv_files', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->text('resource')->nullable();
            $table->text('filename')->nullable();
            $table->integer('count')->nullable();
            $table->text('since')->nullable();
            $table->json('ids')->nullable();
            $table->json('blank_fields')->nullable();
            $table->json('export_fields')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('csv_files');
    }
}
