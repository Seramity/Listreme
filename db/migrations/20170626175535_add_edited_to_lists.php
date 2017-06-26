<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddEditedToLists extends Migration
{
    public function up()
    {
        $this->schema->table('lists', function (Blueprint $table) {
            $table->tinyInteger('edited')->nullable();
        });
    }

    public function down()
    {
        $this->schema->table('lists', function (Blueprint $table) {
            $table->dropColumn('edited');
        });
    }
}