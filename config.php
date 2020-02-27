<?php

/**
 * @author Jonas Lima
 * @version 1.0
 */

require_once 'ORM.php';
error_reporting(E_ALL ^ E_NOTICE);

//show errors
ini_set("log_errors", 0);
ini_set("display_errors", 1);

$mod = (object) [
    "host" => "localhost",
    "user" => "root",
    "password" => "",
    "database" => "cardapio"
];
$a = new ORM();
$GLOBALS["ORM"] = $a->create($mod);

?>
