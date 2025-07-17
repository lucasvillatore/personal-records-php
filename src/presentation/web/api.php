<?php

use App\Application\Router;
use App\Presentation\Controller\HealthCheckController;

$router = new Router();

$router->add('GET', '/health-check', [HealthCheckController::class, 'alive']);
