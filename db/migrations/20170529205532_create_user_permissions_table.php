<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserPermissionsTable extends Migration
{
    public function up()
    {
        $this->schema->create('user_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->tinyInteger('is_admin')->nullable();
            $table->tinyInteger('is_subscriber')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->drop('user_permissions');
    }
}