<?php

require __DIR__ . '/../vendor/autoload.php';

require __DIR__. '/../src/presentation/web/api.php';


$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
