<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommentsTable extends Migration
{
    public function up()
    {
        $this->schema->create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('list_id')->nullable();
            $table->integer('profile_id')->nullable();
            $table->integer('reply_to')->nullable();
            $table->text('content');
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('comments');
    }
}