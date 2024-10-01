<?php

declare(strict_types=1);

// get composer up and replace with
// include __DIR__ . 'vendor/autoload.php'
include __DIR__ . '/autoload.php';

/** @var app\dto\Config $config */
$config = require __DIR__ . '/config/index.php';
$routeFinder = new \app\Routing\RouteFinder(
    viewPath: $config->viewPath,
    aliases: $config->aliases,
);

unset($config); // we hide config from views

$route = $routeFinder->getParsedRoute();
include($route->path); // knows about $route
