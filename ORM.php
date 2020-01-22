<?php

    /**
     * @author Jonas Lima 
     * @version 1.0.0
     */

    class ORM{

        /* INICIO VARIAVEIS */
            /* INICIO DAS VARIAVEIS PRIVADAS */

                private $conn;

            /* FIM DAS VARIAVEIS PRIVADAS */

            /* INICIO DAS VARIAVEIS PUBLICAS */

                public $data;

            /* FIM DAS VARIAVEIS PUBLICAS */

        /* FIM VARIAVEIS */

        /* INICIO METODOS */

            /* METODO CONSTRUTOR */
                function __construct(){
                    $this->allRequires(); 
                }
            /* FIM METODO CONSTRUTOR */
            
            /* INICIO DOS METODOS PUBLICOS */

                /* METODO PARA LIGAÇÃO COM BANCO DE DADOS */
                    public function create($model){
                    
                        $conn = new Conn($model->host, $model->user, $model->password, $model->database);
                        if(!$conn){
                            return (object)[
                                "status" => false,
                                "error" => $conn->error,
                            ];
                        }else{
                            $this->conn = $conn;
                            return (object) [
                                "status" => true,
                                "error" => null,
                            ];
                        }

                    } 
                /* FIM DO METODO DE LIGAÇÃO */

                /* INICIO DOS METODOS DE SELEÇÃO */

                    public function getLike($model){
                        $class = $this->getClassName($model);
                        
                        if (!$class) 
                            return (object) [
                                "status" => false,
                                "error" => "Class Name not found",
                                "data" => []
                            ];
                            
                        $condition = $this->buildConditionLike($model);
                        
                        $res = $this->conn->select("*", strtolower($class."s"), $condition);
                    
                        if(!$res && $this->conn->error != null){
                            return (object) [
                                "status" => false,
                                "error" => $this->conn->error,
                                "data" => [],
                            ];
                        }else{
                            if(!isset($res[0])){
                                return (object) [
                                    "status" => false,
                                    "error" => false,
                                    "data" => [
                                    "msg"=> "Nenhum resultado encontrado!",
                                    ],
                                ];
                            }else{

                                $res = $this->fetchResults($res, $class);
                                
                                return (object) [
                                    "status" => true,
                                    "error" => null,
                                    "data" => $res,
                                ]; 
                            }
                        } 
                    }

                    public function getAll($model){
                        
                        $class = $this->getClassName($model);
                        if (!$class) 
                            return (object) [
                                "status" => false,
                                "error" => "Class Name not found",
                                "data" => []
                            ];
                        
                        $res = $this->conn->select("*", strtolower($class."s"));

                        if(!$res && $this->conn->error != null){
                            return (object) [
                                "status" => false,
                                "error" => $this->conn->error,
                                "data" => [],
                            ];
                        }else{
                            if(!isset($res[0])){
                                return (object) [
                                    "status" => false,
                                    "error" => false,
                                    "data" => [
                                    "msg"=> "Nenhum resultado encontrado!",
                                    ],
                                ];
                            }else{
                                
                                $res = $this->fetchResults($res);

                                return (object) [
                                    "status" => true,
                                    "error" => null,
                                    "data" => $res,
                                ];
                                
                            }
                        }
                    }

                    public function getSome($model){
                        
                        $class = $this->getClassName($model);
                        
                        if (!$class) 
                            return (object) [
                                "status" => false,
                                "error" => "Class Name not found",
                                "data" => []
                            ];
                        
                        $condition = $this->buildConditionEqual($model);
                        
                        $res = $this->conn->select("*", strtolower($class."s"), $condition);

                        if(!$res && $this->conn->error != null){
                            return (object) [
                                "status" => false,
                                "error" => $this->conn->error,
                                "data" => [],
                            ];
                        }else{
                            if(!isset($res[0])){
                                return (object) [
                                    "status" => false,
                                    "error" => false,
                                    "data" => [
                                    "msg"=> "Nenhum resultado encontrado!",
                                    ],
                                ];
                            }else{

                                $res = $this->fetchResults($res, $class);
                                
                                return (object) [
                                    "status" => true,
                                    "error" => null,
                                    "data" => $res,
                                ]; 
                            }
                        }
                    }

                    /* Esse metodo suporta  =, !=,  LIKE, NOT LIKE, BETWEEN, NOT BETWEEN*/
                    public function getAny($model){

                        $class = $this->getClassName($model);

                        if (!$class) 
                        return (object) [
                            "status" => false,
                            "error" => "Class Name not found",
                            "data" => []
                        ];
                    
                        $condition = $this->buildConditionAny($model);
                        if (isset($condition->error)) {
                            return (object) [
                                "status" => false,
                                "error" => $condition->error,
                                "data" => []
                            ];
                        }
                        $res = $this->conn->select("*", strtolower($class."s"), $condition);
                        
                        if(!$res && $this->conn->error != null){
                            return (object) [
                                "status" => false,
                                "error" => $this->conn->error,
                                "data" => [],
                            ];
                        }else{
                            if(!isset($res[0])){
                                return (object) [
                                    "status" => false,
                                    "error" => false,
                                    "data" => [
                                    "msg"=> "Nenhum resultado encontrado!",
                                    ],
                                ];
                            }else{

                                $res = $this->fetchResults($res, $class);
                                
                                return (object) [
                                    "status" => true,
                                    "error" => null,
                                    "data" => $res,
                                ]; 
                            }
                        }
                    }

                /* FIM DOS METODOS DE SELEÇÃO */


            /* FIM DOS METODOS PUBLICOS */

            /* INICIO DOS METODOS PRIVADOS */
                
                /* INICIO DAS FERRAMENTAS MUITO UTILIZADAS */
                    private function fetchResults($res, $class){
                        $i = 0;
                        while(isset($res[$i])){
                            $res[$i] = new  $class($res[$i]);
                            $i++;
                        }
                        return $res;
                    }
                    
                    private function getClassName($model){

                        if(isset($model->class_name)){
                            $class = $model->class_name;
                            unset($model->class_name);
                        }else{
                            $class = get_class($model);
                            if ($class == "stdClass") {
                                $class = false;
                            }
                        }   
                        return $class;
                        
                    }
                /* FIM DAS FERRAMENTAS MUITO UTILIZADAS */

                /* INICIO DOS ESTRUTURADORES DE CONDIÇÃO */
                    private function buildConditionLike($model){
                        $condition = "";
                        $i = 0;
                        foreach ($model as $key => $value) {
                            if($key != "propriedades" && !empty($value)){
                                if($i != 0){
                                    $condition .= " AND ";
                                }else{
                                    $condition .= " WHERE ";
                                }
                                $condition .= $key. " LIKE '%". $value."%' ";
                                $i++;
                            }
                        }
                        if (isset($model->propriedades)) {
                        
                            foreach ($model->propriedades as $key => $value) {
                                if ($key == "LIMIT") {
                                    $later = " ". $key." ".$value." " ;
                                }else{
                                    if($value === true){
                                        $condition .= " ". $key." ";
                                    }elseif($value === false){

                                    }else{
                                        $condition .= " ". $key." ".$value." " ;
                                    }
                                }
                            }   
                            $condition .= $later;
                        }
                        return $condition;
                    }
                    
                    private function buildConditionAny($model){
                        $condition = "";
                        $i = 0;
                        foreach ($model as $key => $value) {
                            if($key != "propriedades" && !empty($value)){
                                if($i != 0){
                                    $condition .= " AND ";
                                }else{
                                    $condition .= " WHERE ";
                                }

                                if ($key == "EQUAL"){
                                    foreach($value as $keys => $values){
                                        $condition .= $keys. " = '". $values."' ";
                                    }
                                }elseif($key == "NOT EQUAL"){
                                    foreach($value as $key => $values){
                                        $condition .= $key. " != '". $values."' ";
                                    }
                                }elseif($key == "LIKE"){
                                    foreach($value as $key => $values){
                                        $condition .= $key. " LIKE '%". $values."%' ";
                                    }
                                }elseif($key == "NOT LIKE"){
                                    foreach($value as $key => $values){
                                        $condition .= $key. " NOT LIKE '". $values."' ";
                                    }
                                }elseif($key == "BETWEEN"){
                                    foreach($value as $key => $values){
                                        $condition .= $key. " BETWEEN '". $values[0]."' AND '". $values[1]."' ";
                                    }
                                }elseif($key == "NOT BETWEEN"){
                                    foreach($value as $key => $values){
                                        $condition .= $key. " NOT BETWEEN '". $values[0]."' AND '". $values[1]."' ";
                                    }
                                }else{
                                    return (object) [
                                        "error" => "Erro na construção, verifique o parametro enviado: " . $key
                                    ];
                                }
                                
                                $i++;
                            }
                        }
                        if (isset($model->propriedades)) {
                        
                            foreach ($model->propriedades as $key => $value) {
                                if ($key == "LIMIT") {
                                    $later = " ". $key." ".$value." " ;
                                }else{
                                    if($value === true){
                                        $condition .= " ". $key." ";
                                    }elseif($value === false){

                                    }else{
                                        $condition .= " ". $key." ".$value." " ;
                                    }
                                }
                            }   
                            $condition .= $later;
                        }
                        return $condition;
                    }

                    private function buildConditionEqual($model){
                        $condition = "";
                        $i = 0;
                        foreach ($model as $key => $value) {
                            if($key != "propriedades" && !empty($value)){
                                if($i != 0){
                                    $condition .= " AND ";
                                }else{
                                    $condition .= " WHERE ";
                                }
                                $condition .= $key. " = '". $value."' ";
                                $i++;
                            }
                        }
                        if (isset($model->propriedades)) {
                        
                            foreach ($model->propriedades as $key => $value) {
                                if ($key == "LIMIT") {
                                    $later = " ". $key." ".$value." " ;
                                }else{
                                    if($value === true){
                                        $condition .= " ". $key." ";
                                    }elseif($value === false){

                                    }else{
                                        $condition .= " ". $key." ".$value." " ;
                                    }
                                }
                            }   
                            $condition .= $later;
                        }
                        return $condition;
                    }
                /* FIM DOS ESTRUTURADORES DE CONDIÇÃO */
                
                /* METODO DE LOAD DAS DEPENDENCIAS */
                    private function allRequires(){
                        require_once './dependences/Conn.class.php';
                    }
                /* FIM METODO DE LOAD DAS DEPENDENCIAS */

            /* FIM DOS METODOS PRIVADOS */

        /* FIM METODOS */
    }