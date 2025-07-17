<?php

require __DIR__ . '/../vendor/autoload.php';

$router = require __DIR__ . '/../src/config/api.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$router->dispatch($method, $uri);
