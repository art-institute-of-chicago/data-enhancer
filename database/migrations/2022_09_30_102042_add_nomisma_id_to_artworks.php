<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNomismaIdToArtworks extends Migration
{
    public function up()
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->text('nomisma_id')->nullable()->after('linked_art_json');
        });
    }

    public function down()
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn('nomisma_id');
        });
    }
}
