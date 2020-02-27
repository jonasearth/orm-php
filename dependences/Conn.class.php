<?php

/**
 * @author Jonas Lima
 * @version 1.0
 */

class Conn
{
    public $error;
    public $factory;

    public $conn;

    function __construct($host, $user, $pass, $db)
    {
        $this->loadAux();

        $conn = mysqli_connect($host, $user, $pass, $db);

        if (!$conn || mysqli_connect_error() != null) {
            $this->error = mysqli_connect_error();

            return false;
        } else {
            $this->conn = $conn;
            $this->error = false;
            $GLOBALS['conn'] = $conn;
            return $conn;
        }
    }

    function haveError()
    {
        if ($this->error) {
            return $this->error;
        } else {
            return false;
        }
    }

    function select($coluna, $tabela, $condicao = "")
    {
        if ($condicao == "") {
            $query = "SELECT " . $coluna . " FROM " . $tabela;
        } else {
            $query = "SELECT " . $coluna . " FROM " . $tabela . " " . $condicao;
        }
        $run = mysqli_query($this->conn, $query);
        if ($this->conn->errno != 0) {
            $this->error = $this->conn->error;
            return false;
        } else {
            if (mysqli_num_rows($run) > 0) {
                $i = 0;
                while ($row = mysqli_fetch_assoc($run)) {
                    $data[$i] = $row;
                    $i++;
                }
                return $data;
            } else {
                return false;
            }
        }
    }

    function insert($tabela, $colunas, $valor)
    {
        if (is_array($valor)) {
            $val = $this->factory->assocToStrWithQuots($valor);
        } else {
            $val = $valor;
        }
        if (is_array($colunas)) {
            $col = $this->factory->assocToStrWithAposts($colunas);
        } else {
            $col = $colunas;
        }
        $query =
            "INSERT INTO `" .
            $tabela .
            "` (" .
            $col .
            ") VALUES (" .
            $val .
            ")";

        if ($this->conn->errno != 0) {
            $this->error = $this->conn->error;
            return false;
        }
        if (mysqli_query($this->conn, $query)) {
            
            return true;
        } else {
            $this->error = mysqli_error($this->conn);
            return false;
        }
    }

    function update($tabela, $coluna, $valor, $condicao = "")
    {
        $i = 0;
        $bld = "";
        while (isset($coluna[$i])) {
            $bld .= " `" . $coluna[$i] . "` = '" . $valor[$i] . "' ";
            $p = $i + 1;
            if (isset($coluna[$p])) {
                $bld .= ",";
            }
            $i++;
        }
        $query = " UPDATE `" . $tabela . "` SET " . $bld . " " . $condicao;
        
        if (mysqli_query($this->conn, $query)) {
            if (mysqli_affected_rows($this->conn) == 0) {
                $this->error =
                    "No rows afected, verify the inputs and try again!";
                return false;
            } else {
                return true;
            }
        } else {
            $this->error = mysqli_error($this->conn);
            return false;
        }
    }

    function delete($tabela, $condicao)
    {
        $query = "DELETE FROM `" . $tabela . "` " . $condicao;

        if (mysqli_query($this->conn, $query)) {
            if (mysqli_affected_rows($this->conn) == 0) {
                $this->error =
                    "No rows afected, verify the inputs and try again!";
                return false;
            } else {
                return true;
            }
        } else {
            $this->error = mysqli_error($this->conn);
            return false;
        }
    }

    function loadAux()
    {
        require_once "Factory.class.php";
        $this->factory = new Factory();
    }
}
