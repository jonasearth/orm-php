<?php

/**
 * @author Jonas Lima
 * @version 1.0
 */

date_default_timezone_set('America/Bahia');

require_once 'ORM.php';
error_reporting(E_ALL ^ E_NOTICE);

//show errors
ini_set("log_errors", 0);
ini_set("display_errors", 1);

$mod = (object) [
    "host" => "localhost",
    "user" => "jonas",
    "password" => "amarsempre",
    "database" => "cardapio"
];
$a = new ORM();
$a->create($mod);
$GLOBALS["ORM"] = $a;
?>
