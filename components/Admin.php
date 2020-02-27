<?php

/**
 * @author Jonas Lima <jonasearth1@gmail.com>
 * @copyright 2020 Jonas Lima
 * @version 1.0.0
 * @api
 * @category Administrador
 */

class Admin
{
    
    public $id;
    public $nome;
    public $usuario;
    public $senha;
    public $email;
    public $acesso;

    
    const VIEW_LEVEL = 1;
    const ADD_LEVEL = 8;
    const EDIT_LEVEL = 10;
    const DELETE_LEVEL = 10;



    function __construct($obj = false)
    {
        if ($obj) {
            foreach ($obj as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public function getAll($met, $route)
    {
        $this->verificarAuth(self::VIEW_LEVEL);
        echo $GLOBALS['ORM']->getAll($this);
        
    }

    public function getById($met, $route)
    {
        $this->verificarAuth(self::VIEW_LEVEL);
        $obj = (object) [
            'class_name' => 'Admin',
            'EQUAL' => (object) [
                'id' => $route[2]['id']
            ],
            'properties' => (object) [
                'LIMIT' => 1
            ]
        ];
        echo $GLOBALS['ORM']->getAny($obj);
    }

    public function registrar($met, $route)
    {
        self::clearPost();
        if ($this->verificarAuth(self::ADD_LEVEL) && $this->verificarPost()) {
            if ($this->verificarCreds($_POST)) {
                self::add();
            } else {
                Returns::simpleMsgError("Login ou Email Utilizado!");
            }
        }
    }

    public function logout(){
        if(isset($_SESSION['admin'])){
            unset($_SESSION['admin']);
            Returns::msgData('Logout efetuado com sucesso', []);
        }
        Returns::simpleMsgError('Você não está logado');
    }

    public function login($met, $route){
        self::clearPost();
        self::verificarLoginPost();
        $obj = (object) [
            'class_name' => 'Admin',
            'EQUAL' => (object) [
                'usuario' => $_POST['usuario'],
                'senha' => $_POST['senha']
            ],
            'properties' => (object) [
                'LIMIT' => 1
            ]
        ];
        $admin = json_decode($GLOBALS['ORM']->getAny($obj));
        if ($admin->status) {
            $this->build($admin->data[0]);
            $this->setSession();
            Returns::msgData('Login efetuado com sucesso', $admin->data[0]);

        }elseif($admin->error == "Nenhum resultado encontrado!"){
            Returns::simpleMsgError('Login ou senha incorretos tente novamente!');
        }else{
            die(json_encode($admin));
        }
    }

    public function atualizar($met, $route){
        self::clearPost();
        if ($this->verificarAuth(self::EDIT_LEVEL) && $this->verificarPost()) {
            if ($this->verificarCredsUp($_POST,$route)) {
                self::update($route);
            } else {
                Returns::simpleMsgError("Login ou Email Utilizado!");
            }
        }
        
    }

    public function deletar($met, $route){
        if ($this->verificarAuth(self::DELETE_LEVEL)){
            $adm = (object) [
                "class_name" => "Admin",
                "identifier" => (object)[
                    "id" => $route[2]['id'],
                ],
            ];
            echo $GLOBALS['ORM']->deleteOne($adm);
        }
        
    }

    private function add()
    {
        $this->build($_POST);
        die($GLOBALS['ORM']->insertOne($this));
    }

    private function update($route)
    {
        $obj= (object)[];
        $obj->class_name = "Admin";
        foreach ($_POST as $key => $value) {
            $obj->sets->$key = $value;
        }
        $obj->identifier->id = $route[2]['id'];
        die($GLOBALS['ORM']->updateOne($obj));
    }

    private function build($var)
    {
        foreach ($var as $key => $value) {
            $this->$key = $value;
        }
    }

    private function verificarAuth($level)
    {
        if (isset($_SESSION['admin']['id'])) {
            if($_SESSION['admin']['acesso'] < $level){
                Returns::simpleMsgError('Você não tem permissão para executar essa ação!');
            }
            return true;
        } else {
            Returns::simpleMsgError('Autenticação necessaria!');
        }
    }

    private function verificarCreds($var)
    {
        if($_POST['acesso'] > $_SESSION['admin']['acesso']){
            Returns::simpleMsgError('Você não pode registrar admins com acesso maior que o seu!');
        }
        $obj = (object) [
            "class_name" => "Admin",
            "EQUAL" => (object) [
                "usuario" => $var['usuario']
            ]
        ];
        $one = json_decode($GLOBALS['ORM']->getAny($obj));
        if ($one->status) {
            return false;
        }
        $obj = (object) [
            "class_name" => "Admin",
            "EQUAL" => (object) [
                "email" => $var['email']
            ]
        ];
        $one = json_decode($GLOBALS['ORM']->getAny($obj));
        if ($one->status) {
            return false;
        }
        return true;
    }

    private function verificarCredsUp($var, $route)
    {
        if($_POST['acesso'] > $_SESSION['admin']['acesso']){
            Returns::simpleMsgError('Você não definir acesso maior que o seu!');
        }
        $obj = (object) [
            "class_name" => "Admin",
            "EQUAL" => (object) [
                "usuario" => $var['usuario']
            ]
        ];
        $one = json_decode($GLOBALS['ORM']->getAny($obj));
        if ($one->status && $one->data[0]->id != $route[2]['id']) {
            return false;
        }
        $obj = (object) [
            "class_name" => "Admin",
            "EQUAL" => (object) [
                "email" => $var['email']
            ]
        ];
        $one = json_decode($GLOBALS['ORM']->getAny($obj));
        if ($one->status && $one->data[0]->id != $route[2]['id']) {
            return false;
        }
        return true;
    }

    private function verificarPost()
    {
        foreach ($this as $key => $value) {
            if (
                $key !== "id" &&
                (!isset($_POST[$key]) || empty($_POST[$key]))
            ) {
                Returns::simpleMsgError(
                    'Verifique os Campos e Tente Novamente!'
                );
            }
        }
        return true;
    }

    private static function clearPost()
    {
        require_once './plugins/Manipulador.php';
        Manipulador::clearPost();
    }

    private function setSession(){
        if ($this->usuario == null) {
            Returns::simpleMsgError(
                'Erro inesperado'
            );
        }
        unset($_SESSION['admin']);
        foreach ($this as $key => $value) {
            $_SESSION['admin'][$key] = $value;
        }

    }

	public static function verificarLoginPost()
	{
		if (!isset($_POST['usuario']) || empty($_POST['usuario']) || !isset($_POST['senha']) || empty($_POST['senha'])) {
            Returns::simpleMsgError(
                'Verifique os Campos e Tente Novamente!'
            );
        }
        return true;
	}
}
