<?php

    /**
     * @author Jonas Lima 
     * @version 1.0.0
     */

    class ORM{

        private $conn;
        public $data;

        function __construct(){
            $this->allRequires(); 
        }

        function create($model){
        
            $conn = new Conn($model->host, $model->user, $model->password, $model->database);
            if(!$conn){
                return (object)[
                    "status" => false,
                    "error" => 's',
                ];
            }else{
                $this->conn = $conn;
                return (object) [
                    "status" => true,
                    "error" => null,
                ];
            }

        } 

        function getAll($model){
        
            if(isset($model->class_name)){
                $class = $model->class_name;
                unset($model->class_name);
            }else{
                $class = get_class($model);
            }
            $res = $this->conn->select("*", strtolower($class."s"));

            if(!$res){
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
                    
                    $i = 0;
                    while(isset($res[$i])){
                        $res[$i] = new  $class($res[$i]);
                        $i++;
                    }
                    return (object) [
                        "status" => true,
                        "error" => null,
                        "data" => $res,
                    ];
                    
                }
            }
        }

        function getSome($model){
            
            if(isset($model->class_name)){
                $class = $model->class_name;
                unset($model->class_name);
            }else{
                $class = get_class($model);
            }

            $condition = $this->buildCondition($model);
            
            $res = $this->conn->select("*", strtolower($class."s"), $condition);

            if(!$res){
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
                    
                    $i = 0;
                    while(isset($res[$i])){
                        $res[$i] = new  $class($res[$i]);
                        $i++;
                    }
                    return (object) [
                        "status" => true,
                        "error" => null,
                        "data" => $res,
                    ];
                    
                }
            }
        }
        
        function buildCondition($model){
            $condition = "";
            $i = 0;
            foreach ($model as $key => $value) {
                if($key != "propriedades"){
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
        

        private function allRequires(){
            require_once './dependences/Conn.class.php';
        }
    }