<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLinkedArtJsonToArtworks extends Migration
{
    public function up()
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->json('linked_art_json')->nullable()->after('support_aat_id');
        });
    }

    public function down()
    {
        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn('linked_art_json');
        });
    }
}
