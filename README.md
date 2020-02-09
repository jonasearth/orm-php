# orm-php

ORM feito para facilitar comunicação no banco de dados

## DEPENDENCIAS

* Conn.class.php
  - Query Builder
* Factory.class.php
  - Manipulador de Strings e Arrays
  
## EXEMPLOS

  1. IMPORTAÇÃO E CONFIGURAÇÃO
  
  ``` php
    
    //importação
    require_once 'ORM.php';
    //passagem de parametros de conexão
    $conn = (object) [
        "host" => "localhost",
        "user" => "usuario",
        "password" => "senha",
        "database" => "banco",
    ];
    //inicialização do objeto
    $ORM = new ORM() ;
    //passagem dos parâmetros para criação da conexão
    $ORM->create($conn); 

  ```

