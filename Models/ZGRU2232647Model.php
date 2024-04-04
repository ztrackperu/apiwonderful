<?php
class ZGRU2232647Model extends Query{

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
    public function superUser($token)
    {
        $sql = "SELECT id ,rol,pass,gmt,modo_temp FROM usuarios_api Where token ='$token'";
        $data = $this->select($sql);
        return $data;
    } 
    //guardarComando($dispositivos[0],8,NA($resul[0]["TelematicId"]),NA($resul[0]["HumiditySetPoint"]),$variable ,$superUsuario['id'])
    public function guardarComando($nombreDispositivo,$comnadoId,$TelemetriaId,$ValorActual,$ValorModificado,$UsuarioModifico)
    {
        $verificar = "SELECT id FROM comandos WHERE telemetria_id = '$TelemetriaId' AND comando_id = '$comnadoId' AND estado_comando = 1";
        $existe = $this->select($verificar);
        if (empty($existe)) {
            $query = "INSERT INTO comandos (nombre_dispositivo,comando_id,telemetria_id,valor_actual,valor_modificado,usuario_modificado) VALUES (?,?,?,?,?,?)";
            $datos = array($nombreDispositivo,$comnadoId,$TelemetriaId,$ValorActual,$ValorModificado,$UsuarioModifico);
            $data = $this->save($query, $datos);
            if ($data == 1) {
                switch ($comnadoId) {
                    case 8:
                        $verificar = "Control command added, humidity will change from ".$ValorActual." % to ".$ValorModificado . "%";
                        break;
                    case 4:
                        $tempOk = $this->validarTemp($UsuarioModifico);
                        if($tempOk['modo_temp']=="F"){
                            $ValorModificado = round(($ValorModificado*9)/5 +32,1);
                        }
                        $verificar = "Control command added, Temperature will change from ".$ValorActual." to ".$ValorModificado . "";
                        break;
                    case 6:
                        $verificar = "Control command added, Ethylene will change from ".$ValorActual." ppm to ".$ValorModificado . "ppm";
                        break;
                    default :
                        $verificar = "Comand successfully created";
                        break;
                }
                $res = $verificar;
            } else {
                $res = "Error creating Command";
            }
        } else {
            $res = "Command already exists";
        }
        return $res;
    }
    public function validarTemp($id)
    {
        $sql = "SELECT modo_temp FROM usuarios_api Where id ='$id'";
        $data = $this->select($sql);
        return $data;
    }

}


?>
