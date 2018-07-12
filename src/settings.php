<?php

// We read environment variables from a file on disk.
if ( ! file_exists(__DIR__ . '/../.env'))
{
    error_log("No '.env' file exists in the base directory. This is required.");
}

$ENVVARS = json_decode(file_get_contents(__DIR__ . '/../.env'), true);
foreach ($ENVVARS as $K => $V)
{
    define ($K, $V);
}



return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'doctrine' => [
             // if true, metadata caching is forcefully disabled
             'dev_mode' => true,

             // path where the compiled metadata info will be cached
             // make sure the path exists and it is writable
             'cache_dir' => APP_ROOT . '/var/doctrine',

             // you should add any other path containing annotated entity classes
             'metadata_dirs' => [APP_ROOT . '/src/Entity'],

             'connection' => [
                 'driver'   => 'pdo_pgsql',
                 'host'     => 'localhost',
                 'port'     => 5432,
                 'dbname'   => 'slimapp',
                 'user'     => 'slimapp',
                 'password' => 'password',
                 'charset'  => 'utf-8'
             ]
         ]
    ],
];
