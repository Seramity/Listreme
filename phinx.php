<?php

require_once __DIR__ . '/bootstrap/app.php';

$config = $container['settings']['db'];

return [
    'paths' => [
        'migrations' => 'db/migrations'
    ],
    'migration_base_class' => 'App\Database\Migrations\Migration',
    'templates' => [
      'file' => 'app/Database/Migrations/MigrationTemplate.php'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default' => [
            'adapter' => $config['driver'],
            'host' => $config['host'],
            'port' => $config['port'],
            'name' => $config['database'],
            'user' => $config['username'],
            'pass' => $config['password']
        ]
    ]
];