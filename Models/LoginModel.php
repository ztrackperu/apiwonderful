<?php
class LoginModel extends Query{

    public function __construct()
    {
        parent::__construct();
    }
    public function validarSesion($user,$pass)
    {
        $sql = "SELECT * FROM usuarios_api WHERE user='$user' AND pass='$pass'";
        $data = $this->select($sql);
        return $data;
    }
    public function ActualizarToken($user_token,$user,$pass)
    {
        $fechaActual= date('Y-m-d H:i:s'); 
        $query = "UPDATE usuarios_api SET token = ? , fecha_token=? WHERE user = ? AND pass = ?";
        $datos = array($user_token,$fechaActual,$user,$pass);
        $data = $this->save($query, $datos);
        if($data){
            $res="ok";
        }else{
            $res="Hubo un error al generar token";
        }
        return $res;
    }
    public function HistoricoUsuario($user,$campo,$valor_anterior,$valor_modificado,$valor_confidencial,$ejecuto,$evento){
        $query = "INSERT INTO h_usuario (user,campo,valor_anterior,valor_modificado,valor_confidencial,ejecuto,evento) VALUES (?,?,?,?,?,?,?)";
        $datos = array($user,$campo,$valor_anterior,$valor_modificado,$valor_confidencial,$ejecuto,$evento);
        $data = $this->save($query, $datos);
        return $data;

    }

}