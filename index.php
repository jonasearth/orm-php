<?php
session_start();
require './vendor/autoload.php';
require_once './config.php';
require './routes.php';

$dispatcher = FastRoute\simpleDispatcher(function (
    FastRoute\RouteCollector $r
) {
    Routes::init($r);
});

// Fetch method and URI from somewhere
$parm = Routes::getParam();

Routes::startRoutes($dispatcher, $parm);
