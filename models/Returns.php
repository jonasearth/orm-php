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

    private function jsonSend($data)
    {
        echo json_encode($data);
        die();
    }
}
?>
