<?php
class ConfigModel extends Query{

    public function __construct()
    {
        parent::__construct();
    }
    public function getGmt($token)
    {
        $sql = "SELECT gmt,estado FROM usuarios_api Where token ='$token'";
        $data = $this->select($sql);
        return $data;
    }
    public function validar_token($token)
    {
        $sql = "SELECT estado,fecha_token FROM usuarios_api Where token ='$token'";
        $data = $this->select($sql);
        return $data;
    }
    public function superUser($token)
    {
        $sql = "SELECT id FROM usuarios_api Where token ='$token'";
        $data = $this->select($sql);
        return $data;
    }
    public function getTemp($token)
    {
        $sql = "SELECT modo_temp FROM usuarios_api Where token ='$token' ";
        $data = $this->select($sql);
        return $data;
    }
    public function getRol($token)
    {
        $sql = "SELECT rol FROM usuarios_api Where token ='$token' ";
        $data = $this->select($sql);
        return $data;
    }

    public function cambiarGmt($token, $num)
    {
        $query = "UPDATE usuarios_api SET gmt = ? WHERE token = ?";
        $datos = array( $num,$token);
        $data = $this->save($query, $datos);
        
        return $data;
    }
    public function cambiarTemp($token, $num)
    {
        $query = "UPDATE usuarios_api SET modo_temp = ? WHERE token = ? ";
        $datos = array( $num,$token);
        $data = $this->save($query, $datos);
        
        return $data;
    }
    public function cambiarRol($token, $num)
    {
        $query = "UPDATE usuarios_api SET rol = ? WHERE token = ? ";
        $datos = array( $num,$token);
        $data = $this->save($query, $datos);
        
        return $data;
    }
    public function CreateUser($user_token,$user,$pass,$rol,$gmt,$temp)
    {
        $verificar = "SELECT user FROM usuarios_api WHERE user = '$user'";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $query = "INSERT INTO usuarios_api (token,user,pass,rol,gmt,modo_temp) VALUES (?,?,?,?,?,?)";
            $datos = array($user_token,$user,$pass,$rol,$gmt,$temp);
            $data = $this->save($query, $datos);
            if ($data == 1) {
                $res = "User successfully created";
            } else {
                $res = "error creating User";
            }
        } else {
            $res = "User already exists";
        }
        return $res;
    }
    public function ActulizarPassword($passAntiguo,$passNuevo,$token)
    {
        $verificar = "SELECT user FROM usuarios_api WHERE pass = '$passAntiguo' AND token='$token'";
        $existe = $this->select($verificar);
        if(!empty($existe)) {
            $query = "UPDATE usuarios_api SET pass = ? WHERE token = ? ";
            $datos = array( $passNuevo,$token);
            $data = $this->save($query, $datos);
            if ($data == 1) {
                $res = "ok";
            } else {
                $res = "Error updating the pass";
            }
        }else {
            $res = "The pass is incorrect";
        }
        return $res;

    }
    public function MyConfig($token){
        $verificar = "SELECT user,rol,gmt,modo_temp FROM usuarios_api WHERE token = '$token'";
        $existe = $this->select($verificar);
        if(!empty($existe)) {
            $res =$existe;
        }
        else{
            $res = "Error requesting Configuration";

        }
        return $res;

    }
    public function exiteUsuario($user)
    {
        $sql = "SELECT * FROM usuarios_api Where user ='$user' ";
        $data = $this->select($sql);
        return $data;
    }
    public function resetPass($token,$passNuevo)
    {
        $query = "UPDATE usuarios_api SET pass = ? WHERE token = ? ";
        $datos = array( $passNuevo,$token);
        $data = $this->save($query, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "error resetting the pass";
        }       
        return $res;

    }
    public function bloquearUsuario($token)
    {
        $query = "UPDATE usuarios_api SET estado = 0 WHERE token = ? ";
        $datos = array( $token);
        $data = $this->save($query, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "Error Blocking User";
        }       
        return $res;

    }
    public function activarUsuario($token)
    {
        $query = "UPDATE usuarios_api SET estado = 1 WHERE token = ? ";
        $datos = array( $token);
        $data = $this->save($query, $datos);
        if ($data == 1) {
            $res = "ok";
        } else {
            $res = "Error Activating User";
        }       
        return $res;

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
    public function HistoricoUsuario($user,$campo,$valor_anterior,$valor_modificado,$valor_confidencial,$ejecuto,$evento){
        $query = "INSERT INTO h_usuario (user,campo,valor_anterior,valor_modificado,valor_confidencial,ejecuto,evento) VALUES (?,?,?,?,?,?,?)";
        $datos = array($user,$campo,$valor_anterior,$valor_modificado,$valor_confidencial,$ejecuto,$evento);
        $data = $this->save($query, $datos);
        return $data;

    }
    public function dataHistorica($token)
    {
        $sql = "SELECT gmt,modo_temp ,rol,estado,user FROM usuarios_api Where token ='$token' ";
        $data = $this->select($sql);
        return $data;
    }


}