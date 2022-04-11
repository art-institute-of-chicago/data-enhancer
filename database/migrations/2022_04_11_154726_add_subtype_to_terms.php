<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubtypeToTerms extends Migration
{
    public function up()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->string('subtype', 4)->nullable()->after('title');
        });
    }

    public function down()
    {
        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn('subtype');
        });
    }
}
