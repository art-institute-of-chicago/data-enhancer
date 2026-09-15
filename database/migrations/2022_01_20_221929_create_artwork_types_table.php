<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('artwork_types', function (Blueprint $table) {
            $table->integer('id')->signed()->primary();
            $table->text('title')->nullable();
            $table->integer('aat_id')->signed()->nullable();
            $table->timestamp('source_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artwork_types');
    }
};
