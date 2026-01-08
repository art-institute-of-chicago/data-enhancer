<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdatedAtIndexToAgents extends Migration
{
    private $tables = ['agents', 'artworks', 'artwork_types', 'places', 'terms'];

    public function up()
    {
        foreach ($this->tables as $t)
        {
            Schema::table($t, function (Blueprint $table) {
                $table->index('updated_at');
            });
        }
    }

    public function down()
    {
        foreach ($this->tables as $t)
        {
            Schema::table($t, function (Blueprint $table) {
                $table->dropIndex('updated_at');
            });
        }
    }
}
