<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    public function up()
    {
        $this->schema->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username');
            $table->string('email');
            $table->string('password');
            $table->tinyInteger('active')->nullable();
            $table->string('active_hash')->nullable();
            $table->string('recover_hash')->nullable();
            $table->string('name')->nullable();
            $table->text('bio')->nullable();
            $table->tinyInteger('gravatar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('users');
    }
}