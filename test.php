<?php

require_once 'ORM.php';
require_once 'User.php';

  $mod = (object) [
    "host" => "localhost",
    "user" => "root",
    "password" => "",
    "database" => "orm",
  ];

;
  $a = new ORM();
  if($a->create($mod)->status){
    $user = new User(); 
    $users = $a->getAll( (object) [
      "class_name" => "User",
    ]);
    var_dump($users);
  }

