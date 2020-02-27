<?php


class Manipulador{
    public static function clearPost(){
        foreach ($_POST as $key => $value) {
            $_POST[$key] = mysqli_real_escape_string($GLOBALS['conn'], trim($_POST[$key]));
        }
    }
}