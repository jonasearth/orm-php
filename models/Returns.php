<?php

class Returns
{
    public static function simpleMsgError($msg)
    {
        $data = [
            "mensagem" => $msg,
            "error" => true
        ];
        self::jsonSend($data);
    }

    public static function msgData($msg, $data){

        $dat = [
            "mensagem" => $msg,
            "error" => false,
            "data" => $data,
        ];
        self::jsonSend($dat);
    }
    private static function jsonSend($data)
    {
        die(json_encode($data));
    }
}
?>
