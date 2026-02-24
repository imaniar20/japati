<?php

use Illuminate\Support\Str;

$ekinerjaTahun = [];
$tahunKinerja = TAHUN_KINERJA;
if (strlen(TAHUN_KINERJA) != 4) {
    $tahunKinerja = substr(TAHUN_KINERJA, 4);
}

for ($tahun = 2021; $tahun <= TAHUN_KINERJA; $tahun++) {
    $ekinerjaTahun["ekinerja_{$tahun}"] = [
        'driver' => 'pgsql',
        'read' => [
            'host' => env("DB_READ_HOST_EKINERJA_{$tahun}", env("DB_HOST_EKINERJA_{$tahun}", 'localhost')),
            'port' => env("DB_READ_PORT_EKINERJA_{$tahun}", env("DB_PORT_EKINERJA_{$tahun}", '5432')),
            'database' => env("DB_READ_DATABASE_EKINERJA_{$tahun}", env("DB_DATABASE_EKINERJA_{$tahun}", "erk_ekinerja_{$tahun}")),
            'username' => env("DB_READ_USERNAME_EKINERJA_{$tahun}", env("DB_USERNAME_EKINERJA_{$tahun}", 'forge')),
            'password' => env("DB_READ_PASSWORD_EKINERJA_{$tahun}", env("DB_PASSWORD_EKINERJA_{$tahun}", 'forge')),
        ],
        'write' => [
            'host' => env("DB_WRITE_HOST_EKINERJA_{$tahun}", env("DB_HOST_EKINERJA_{$tahun}", 'localhost')),
            'port' => env("DB_WRITE_PORT_EKINERJA_{$tahun}", env("DB_PORT_EKINERJA_{$tahun}", '5432')),
            'database' => env("DB_WRITE_DATABASE_EKINERJA_{$tahun}", env("DB_DATABASE_EKINERJA_{$tahun}", "erk_ekinerja_{$tahun}")),
            'username' => env("DB_WRITE_USERNAME_EKINERJA_{$tahun}", env("DB_USERNAME_EKINERJA_{$tahun}", 'forge')),
            'password' => env("DB_WRITE_PASSWORD_EKINERJA_{$tahun}", env("DB_PASSWORD_EKINERJA_{$tahun}", 'forge')),
        ],
        'charset' => 'utf8',
        'prefix' => '',
        'search_path' => 'public',
        'sslmode' => 'prefer',
    ];
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => array_merge($ekinerjaTahun, [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'pgsql2' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST_2', '127.0.0.1'),
            'port' => env('DB_PORT_2', '5432'),
            'database' => env('DB_DATABASE_2', 'laravel'),
            'username' => env('DB_USERNAME_2', 'root'),
            'password' => env('DB_PASSWORD_2', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'prefix' => '',
            'prefix_indexes' => true,
            // 'encrypt' => env('DB_ENCRYPT', 'yes'),
            // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
        ],

        'ekinerja' => [
            'driver' => 'pgsql',
            'read' => [
                'host' => env('DB_READ_HOST_EKINERJA', env('DB_HOST_EKINERJA', 'localhost')),
                'port' => env('DB_READ_PORT_EKINERJA', env('DB_PORT_EKINERJA', '5432')),
                'database' => env('DB_READ_DATABASE_EKINERJA', env('DB_DATABASE_EKINERJA', 'forge')),
                'username' => env('DB_READ_USERNAME_EKINERJA', env('DB_USERNAME_EKINERJA', 'forge')),
                'password' => env('DB_READ_PASSWORD_EKINERJA', env('DB_PASSWORD_EKINERJA', '')),
            ],
            'write' => [
                'host' => env('DB_WRITE_HOST_EKINERJA', env('DB_HOST_EKINERJA', 'localhost')),
                'port' => env('DB_WRITE_PORT_EKINERJA', env('DB_PORT_EKINERJA', '5432')),
                'database' => env('DB_WRITE_DATABASE_EKINERJA', env('DB_DATABASE_EKINERJA', 'forge')),
                'username' => env('DB_WRITE_USERNAME_EKINERJA', env('DB_USERNAME_EKINERJA', 'forge')),
                'password' => env('DB_WRITE_PASSWORD_EKINERJA', env('DB_PASSWORD_EKINERJA', '')),
            ],
            'charset' => 'utf8',
            'prefix' => '',
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'siap' => [
            'driver' => 'pgsql',
            // 'host' => env('DB_HOST_SIAP', 'localhost'),
            'read' => [
                'host' => [
                    env('DB_HOST_SIAP', 'localhost'),
                ],
            ],
            'write' => [
                'host' => [
                    'read-only', // read only, disable write
                ],
            ],
            'port' => env('DB_PORT_SIAP', '5432'),
            'database' => env('DB_DATABASE_SIAP', 'forge'),
            'username' => env('DB_USERNAME_SIAP', 'forge'),
            'password' => env('DB_PASSWORD_SIAP', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

        'simpeg' => [
            'driver' => 'pgsql',
            'read' => [
                'host' => [
                    env('DB_HOST_SIMPEG', 'localhost'),
                ],
            ],
            'write' => [
                'host' => [
                    env('DB_HOST_SIMPEG', 'localhost'),
                    // 'read-only', // read only, disable write
                ],
            ],
            'port' => env('DB_PORT_SIMPEG', '5432'),
            'database' => env('DB_DATABASE_SIMPEG', 'forge'),
            'username' => env('DB_USERNAME_SIMPEG', 'forge'),
            'password' => env('DB_PASSWORD_SIMPEG', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'search_path' => 'public',
            'sslmode' => 'prefer',
        ],

    ]),

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
            'persistent' => env('REDIS_PERSISTENT', false),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
