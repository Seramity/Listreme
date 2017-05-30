<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddPositionToLists extends Migration
{
    public function up()
    {
        $this->schema->table('lists', function (Blueprint $table) {
            $table->integer('position');
        });
    }

    public function down()
    {
        $this->schema->table('lists', function (Blueprint $table) {
            $table->dropColumn('position');
        });
    }
}