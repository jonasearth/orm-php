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
$CONN = (object) [
    "host" => "localhost",
    "user" => "usuario",
    "password" => "senha",
    "database" => "banco"
];
//inicialização do objeto
$ORM = new ORM();
//passagem dos parâmetros para criação da conexão
$ORM->create($CONN);
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
                $OBJ = (object) [
                    // é obrigatorio a passagem da variavel classname para identificação da tabela
                    "class_name" => "User",
                    // as outras variaveis servem para identificação das colunas
                    "id" => 1,
                    "nome" => "jonas"
                ];
                ```

    1. SELEÇÂO

        - Metodos de seleção se baseiam em obter algum dado do banco

        1. metodo getAll retorna todos os resultados da tabela sem a necessidade de envio de parametros:

            1. Com Modelo:

            ```php
            //abertura do objeto modelo
            $User = new User();
            //passagem do modelo para o metodo
            $retorno = $ORM->getAll($User);
            //o metodo pegara o nome da classe para busca da tabela e usara o modelo para retorno do objeto
            ```

            - Dentro de \$retorno terá um json com status, error, e data que terá um array com resultados da busca:

            ```json
            {
                "status": true,
                "error": null,
                "data": [
                    {
                        "id": "2",
                        "nome": "david",
                        "senha": "tavares"
                    },
                    {
                        "id": "5",
                        "nome": "jonas",
                        "senha": "amarsempre"
                    },
                    {
                        "id": "6",
                        "nome": "bacelar",
                        "senha": "banana123"
                    },
                    {
                        "id": "7",
                        "nome": "joao",
                        "senha": "35cmeu"
                    }
                ]
            }
            ```

            2. Sem modelo:
                ```php
                //abertura do objeto neutro com passagem obrigatoria do class_name
                $User = (object) [
                    "class_name" => "User"
                ];
                //passagem do modelo para o metodo
                $retorno = $ORM->getAll($User);
                //o metodo pegara o nome da classe para busca da tabela e usara o modelo para retorno do objeto
                ```

            - O retorno será identico ao metodo anterior.

        2. metodo getAny retorna os resultados da tabela baseado no envio dos parametros:
           \*Esse metodo suporta os seguintes parametros
           -EQUAL
           -NOT EQUAL
           -LIKE
           -NOT LIKE
           -BETWEEN
           -NOT BETWEEN
           -properties

            ###### Modelo de uso


            ```php
            //abertura do objeto neutro com passagem obrigatoria do class_name
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
            //passagem do modelo para o metodo
            $retorno = $ORM->getAny($User);
            //o metodo pegara o nome da classe para busca da tabela e usara o modelo para retorno do objeto
            ```

            - Dentro de \$retorno terá:

            ```json
            {
                "status": true,
                "error": null,
                "data": [
                    {
                    "id": "2",
                    "nome": "david",
                    "senha": "tavares"
                    },
                    {
                    "id": "6",
                    "nome": "bacelar",
                    "senha": "banana123"
                    },
                    {
                    "id": "7",
                    "nome": "joao",
                    "senha": "35cmeu"
                    }
                ]
                }
            ```

    2. INSERÇÃO
        1. metodos utilizados para inserir conteudo. ex:
            1. metodo insertOne
                1. Usando modelo:
                    ```php
                    //abertura do objeto modelo
                    $User = new User();
                    $User->nome = "teste";
                    $User->senha = "senhateste";
                    //passagem do modelo para o metodo
                    $retorno = $ORM->insertOne($User);
                    //o metodo pegara o nome da classe para busca da tabela e usara o modelo para retorno do objeto
                    ```
                    - Caso tenha sucesso o retorno será:
                    ```json
                    {
                        "status": true,
                        "error": null,
                        "data": {
                            "msg": "Inserido com Sucesso"
                        }
                    }
                    ```
                2. Sem modelo:
                    ```php

                    $User = (object) [
                        "class_name" => "User",
                        "nome" => "teste",
                        "senha" => "senhateste"
                    ];

                    $retorno = $ORM->insertOne($User);

                    ```

            2. Metodo insertN()
                - Funciona da mesma maneira porem você irá passar um array com varios objetos para a inserção

                ```php

                    $User = new User();
                    $User->nome = "teste";
                    $User->senha = "senhateste";
                    $User2 = new User();
                    $User2->nome = "teste2";
                    $User2->senha = "senhateste2";
                    $users = [
                        $User,
                        $User2
                    ];

                    $retorno = $ORM->insertN($users);

                ```
                - O retorno vira em forma de array correspondente a ordem passada onde cada um dos objetos enviados terá sua propria resposta
    3. UPDATE
        - metodo updateOne()
            ###### esse metodo não tem suporte a modelos
            -passagem dos parametros class_name,  identifier para saber qual linha da tabela deve se editar e sets para saber o que deve mudar e para qual valor

            ```php

                    $User = (object) [
                        "class_name" => "User",
                        "identifier" => (object)[
                            "id" => 15,
                        ],
                        "sets" => (object)[
                            "nome" => "testeatt",
                            "senha" => "senhatesteatt"
                        ]

                    ];

                    $retorno = $ORM->updateOne($User);

            ```
            - O retorno caso sucesso será:
                ```json
                    {
                        "status": true,
                        "error": null,
                        "data": {
                            "msg": "Atualizado com Sucesso"
                        }
                    }
                ```

    4. DELETE
        - metodo deleteOne()
            ###### esse metodo não tem suporte a modelos
            -passagem dos parametros class_name,  identifier para saber qual linha da tabela deve se deletar

            ```php

                    $User = (object) [
                        "class_name" => "User",
                        "identifier" => (object)[
                            "id" => 15,
                        ],

                    ];

                    $retorno = $ORM->deleteOne($User);

            ```
            - O retorno caso sucesso será:
                ```json
                    {
                        "status": true,
                        "error": null,
                        "data": {
                            "msg": "Deletado com Sucesso"
                        }
                    }
                ```
