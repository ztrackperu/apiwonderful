<?php
class ZGRU2008220Model extends Query{

    public function __construct()
    {
        parent::__construct();
    }
    public function validar_token($token)
    {
        $sql = "SELECT estado,fecha_token FROM usuarios_api Where token ='$token'";
        $data = $this->select($sql);
        return $data;
    }
    public function Actualizar_Fecha_token($fechaActual,$api)
    {
        $query = "UPDATE usuarios_api SET fecha_token = ? WHERE token = ? ";
        $datos = array( $fechaActual , $api);
        $data = $this->save($query, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "token date updated";
        }
        return $res;
    }
    public function getGmt_Temp($token)
    {
        $sql = "SELECT gmt,modo_temp FROM usuarios_api Where token ='$token'";
        $data = $this->select($sql);
        return $data;
    }

}


?>
