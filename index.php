<?php
require './vendor/autoload.php';
require './routes.php';

$dispatcher = FastRoute\simpleDispatcher(function (
    FastRoute\RouteCollector $r
) {
    Routes::init($r);
});

// Fetch method and URI from somewhere
$parm = Routes::getParam();

Routes::startRoutes($dispatcher, $parm);
