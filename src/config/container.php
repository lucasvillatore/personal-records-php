<?php

use App\Application\Container;
use App\Application\Database;
use App\Core\Repository\PRRepositoryInterface;
use App\Infra\Database\MySQL\PRRepository;

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

$container->bind(PRRepositoryInterface::class, PRRepository::class);


return $container;
