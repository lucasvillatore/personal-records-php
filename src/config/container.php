<?php

use App\Application\Container;
use App\Application\MySQLPDO;
use App\Core\Repository\PRRepositoryInterface;
use App\Infra\Database\MySQL\PRRepository;

$databaseConfig = require __DIR__ . '/database.php';

$container = new Container();

$container->bind(MySQLPDO::class, function () use ($databaseConfig) {
    return new MySQLPDO(
        $databaseConfig['host'],
        $databaseConfig['db'],
        $databaseConfig['user'],
        $databaseConfig['pass']
    );
});

$container->bind(PRRepositoryInterface::class, PRRepository::class);

return $container;
