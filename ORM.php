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
        
            if(isset($model->class_name))
                $class = $model->class_name;
            else{
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


        private function allRequires(){
            require_once './dependences/Conn.class.php';
        }
    }