<?php
class HistoryModel extends Query{

    public function __construct()
    {
        parent::__construct();
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
    public function validarSesionH($token)
    {
        $sql = "SELECT id,user,gmt FROM usuarios_api WHERE token='$token'";
        $data = $this->select($sql);
        return $data;
    }
    public function validar_tokenH($token)
    {
        $sql = "SELECT estado,fecha_token,rol FROM usuarios_api Where token ='$token'";
        $data = $this->select($sql);
        return $data;
    }
    public function DatosHistoricos($user,$id)
    {
        if($id==1){
            $sql = "SELECT user,campo,valor_anterior,valor_modificado,ejecuto,evento,fecha_evento FROM h_usuario ";
        }else{
            $sql = "SELECT user,campo,valor_anterior,valor_modificado,ejecuto,evento,fecha_evento FROM h_usuario Where user ='$user'";        
        }
        $data = $this->selectAll($sql);
        return $data;
    }


}

?>