<?php
return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/migrations',
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'development',
        'development' => [
            'adapter' => 'pgsql',
            'host'    => getenv('DB_HOST'),
            'port'    => getenv('DB_PORT') ?: 5432,
            'name'    => getenv('DB_NAME'),
            'user'    => getenv('DB_USER'),
            'pass'    => getenv('DB_PASSWORD'),
            'charset' => 'utf8',
        ],
    ],
];
