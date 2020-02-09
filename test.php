<?php
require_once 'config.php';
require_once 'User.php';

$obj = (object) [
    "class_name" => "user",
    "identifier" => (object) [
        "id" => 1
    ]
];

echo json_encode($GLOBALS["ORM"]->deleteOne($obj));

// echo json_encode($users);
