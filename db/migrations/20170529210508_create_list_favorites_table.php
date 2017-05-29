<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListFavoritesTable extends Migration
{
    public function up()
    {
        $this->schema->create('list_favorites', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('list_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('list_favorites');
    }
}