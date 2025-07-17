<?php

use App\Application\Router;
use App\Presentation\Controller\HealthCheckController;
use App\Presentation\Controller\PersonalRecordController;

$container = require __DIR__ . '/container.php';

$router = new Router($container);

$router->add('GET', '/health-check', [HealthCheckController::class, 'alive']);
$router->add('GET', '/personal-records', [PersonalRecordController::class, 'fetchPRFromUsers']);

return $router;
