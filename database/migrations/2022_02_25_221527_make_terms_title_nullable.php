<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeTermsTitleNullable extends Migration
{
    public function up()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->text('title')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->text('title')->nullable(false)->change();
        });
    }
}
