<?php

use $useClassName;
use Illuminate\Database\Schema\Blueprint;

class $className extends $baseClassName
{
    public function up()
    {
        $this->schema->create('', function (Blueprint $table) {
            // Create a table
        });

        $this->schema->table('', function (Blueprint $table) {
            // Make changes to a table
        });
    }

    public function down()
    {
        $this->schema->table('', function (Blueprint $table) {
            // Rollback changes to a table
        });

        $this->schema->drop(''); // Drop a table
    }
}