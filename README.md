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

    require_once 'ORM.php';
    $conn = (object) [
        "host" => "localhost",
        "user" => "usuario",
        "password" => "senha",
        "database" => "banco",
    ];
    $ORM = new ORM() ;
    $ORM->create($conn);

  ```

