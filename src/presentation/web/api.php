<?php

use App\Presentation\Controller\HealthCheckController;
use App\Presentation\Web\Router\Router;

$router = new Router();

$router->add('GET', '/health-check', [HealthCheckController::class, 'alive']);
