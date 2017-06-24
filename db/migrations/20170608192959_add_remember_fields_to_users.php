<?php

use App\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddRememberFieldsToUsers extends Migration
{
    public function up()
    {
        $this->schema->table('users', function (Blueprint $table) {
            $table->string('remember_identifier')->nullable();
            $table->string('remember_token')->nullable();
        });
    }

    public function down()
    {
        $this->schema->table('', function (Blueprint $table) {
            $table->dropColumn('remember_identifier');
            $table->dropColumn('remember_token');
        });
    }
}