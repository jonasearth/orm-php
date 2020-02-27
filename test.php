<?php
require_once 'config.php';

$User = (object) [
    "class_name" => "User",
    "identifier" => (object) [
        "id" => 14
    ]
];

//passagem do modelo para o metodo

echo $GLOBALS["ORM"]->deleteOne($User);

// echo json_encode($users);
