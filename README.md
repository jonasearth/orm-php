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

2. METODOS DE COMUNICAÇÂO

    0. PADRÕES

        - o envio de dados para comunição é feita atravez de objetos
        - esses objetos podem ser enviados de 2 maneiras:

            - usando um modelo de objeto:

                ```php
                //obs: o nome da class deve ser igual ao do banco de dados no singular
                //ou seja, na busca ele vai procurar pela tabela 'users'
                class User
                {
                    //as variaveis devem ser iguais as colunas do banco
                    public $id;
                    public $nome;
                    public $senha;

                    //todos os modelos devem ter por padrao o construtor dessa maneira
                    function __construct($obj = false)
                    {
                        if ($obj) {
                            foreach ($obj as $key => $value) {
                                $this->$key = $value;
                            }
                        }
                    }
                }
                ```

            - utilizando uma classe neutra:
                ```php
                //é obrigatório o uso do (object)
                $objeto = (object) [
                    // é obrigatorio a passagem da variavel classname para identificação da tabela
                    "class_name" => "User",
                    // as outras variaveis servem para identificação das colunas
                    "id" => 1,
                    "nome" => "jonas"
                ];
                ```

    1. SELEÇÂO

    Metodos de seleção se baseiam em obter algum dado do banco
