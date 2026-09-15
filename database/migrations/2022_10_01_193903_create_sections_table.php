<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->signed()->primary();
            $table->text('title')->nullable();
            $table->text('accession')->nullable();
            $table->integer('artwork_id')->signed()->nullable();
            $table->integer('source_id')->signed();
            $table->unsignedInteger('publication_id');
            $table->longText('content')->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
