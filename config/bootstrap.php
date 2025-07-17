<?php

use App\Application\Container;
use App\Application\Database;


$databaseConfig = require __DIR__ . '/database.php';

$container = new Container();

$container->bind('database', function () use ($databaseConfig) {
    return new Database(
        $databaseConfig['host'],
        $databaseConfig['db'],
        $databaseConfig['user'],
        $databaseConfig['pass']
    );
});

return $container;