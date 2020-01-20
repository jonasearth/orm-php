<?php

  class User{
    public $id;
    public $nome;
    public $senha;

    function __construct($obj = false){
      if($obj){
        foreach ($obj as $key => $value) {
          $this->$key = $value;
        }
      }
    }

  }