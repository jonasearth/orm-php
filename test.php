<?php
        require_once('config.php');
        require_once 'User.php';
        
        $user = new User();

        $users = $GLOBALS["ORM"]->getSome((object) [
            "class_name" => "User", 
            "id" => "' or id = '2"
        ]);
        echo json_encode($users);



