<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePubsFieldsNullable extends Migration
{
    public function up()
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->text('site')->nullable()->change();
            $table->text('alias')->nullable()->change();
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->integer('source_id')->nullable()->change();
            $table->unsignedInteger('publication_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('publications', function (Blueprint $table) {
            $table->text('site')->nullable(false)->change();
            $table->text('alias')->nullable(false)->change();
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->integer('source_id')->nullable(false)->change();
            $table->unsignedInteger('publication_id')->nullable(false)->change();
        });
    }
}
