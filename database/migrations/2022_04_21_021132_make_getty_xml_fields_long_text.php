<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeGettyXmlFieldsLongText extends Migration
{
    public function up()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->longText('ulan_xml')->change();
        });

        Schema::table('artwork_types', function (Blueprint $table) {
            $table->longText('aat_xml')->change();
        });

        Schema::table('places', function (Blueprint $table) {
            $table->longText('tgn_xml')->change();
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->longText('aat_xml')->change();
        });
    }

    public function down()
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->text('ulan_xml')->change();
        });

        Schema::table('artwork_types', function (Blueprint $table) {
            $table->text('aat_xml')->change();
        });

        Schema::table('places', function (Blueprint $table) {
            $table->text('tgn_xml')->change();
        });

        Schema::table('terms', function (Blueprint $table) {
            $table->text('aat_xml')->change();
        });
    }
}
