<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateListsTable extends Migration
{
    public function up()
    {
        $this->schema->create('lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title');
            $table->text('content');
            $table->string('category');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('lists');
    }
}