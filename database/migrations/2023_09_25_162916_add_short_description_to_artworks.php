<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShortDescriptionToArtworks extends Migration
{
    public function up()
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->text('short_description')->nullable();
        });
    }

    public function down()
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn('short_description');
        });
    }
}
