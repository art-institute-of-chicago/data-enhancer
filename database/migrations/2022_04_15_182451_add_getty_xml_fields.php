<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGettyXmlFields extends Migration
{
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->text('ulan_xml')->nullable()->after('ulan_certainty');
        });

        Schema::table('artwork_types', function (Blueprint $table) {
            $table->text('aat_xml')->nullable()->after('aat_id');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->text('tgn_xml')->nullable()->after('tgn_id');
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->text('aat_xml')->nullable()->after('aat_id');
        });
    }

    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropColumn('ulan_xml');
        });

        Schema::table('artwork_types', function (Blueprint $table) {
            $table->dropColumn('aat_xml');
        });

        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn('tgn_xml');
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn('aat_xml');
        });
    }
}
