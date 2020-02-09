# orm-php

ORM feito para facilitar comunicação no banco de dados

## DEPENDENCIAS

-   Conn.class.php
    -   Query Builder
-   Factory.class.php

    -   Manipulador de Strings e Arrays

## EXEMPLOS

1. IMPORTAÇÃO E CONFIGURAÇÃO

```php
//importação
require_once 'ORM.php';
//passagem de parametros de conexão
$conn = (object) [
    "host" => "localhost",
    "user" => "usuario",
    "password" => "senha",
    "database" => "banco"
];
//inicialização do objeto
$ORM = new ORM();
//passagem dos parâmetros para criação da conexão
$ORM->create($conn);
```

O metodo create() retorna um conteudo em json com um status e um error :

```json
{
    "status": true,
    "error": null
}
```

-   o parametro status retorna true quando a conexao foi feita, false caso haja algum problema.
-   o parametro error retorna o erro caso haja, caso não retorna null.
