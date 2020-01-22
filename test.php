<?php
        require_once('config.php');
        require_once 'User.php';
        
        $user = new User(); 
        $user->id = 2;
        $users = $GLOBALS["ORM"]->getAny((object) [
            "class_name" => "User",
            "NOT EQUAL" => [
                "nome" => "jonas",
            ],
            "NOT LIKE" => [
                "senha" => "am"
            ],
            "NOT BETWEEN" =>[
                "id" => [
                    0,
                    1
                ],
            ],
            "propriedades" => [
                "LIMIT" => 5,
                "ORDER" => "BY id desc"
            ]
        ]);
        var_dump($users);
    

