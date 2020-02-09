<?php
require_once 'config.php';
require_once 'User.php';

$User = (object) [
    "class_name" => "User",
    //ira buscar todos os resultados que contem "a" no nome
    "LIKE" => (object) [
        "nome" => "a"
    ],
    //e que a senha não seja igual a "amarsempre"
    "NOT EQUAL" => (object) [
        "senha" => "amarsempre"
    ],
    //properties definem algumas propriedades para a busca como limite de dados e tipo de ordem
    "properties" => (object) [
        "LIMIT" => 2,
        "ORDER" => "BY ID DESC"
    ]
];

echo $GLOBALS["ORM"]->getAny($User);

// echo json_encode($users);
