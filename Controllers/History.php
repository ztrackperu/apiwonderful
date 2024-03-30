<?php
class History extends Controller
{
    public function __construct($api)
    {
        parent::__construct();
        //consulta del token de la API con su ultima fecha 
        $token = $this->model->validar_tokenH($api);
        //echo var_dump(($token));     
        if($token==false){
            header("location: " . base_url."api/Configuracion/NoAutorizado/".$api);
        }else{
            if($token['estado']!=1){
                header("location: " . base_url."api/Configuracion/Inactivo/".$api);
            }
            if($token['rol']!=3){
                header("location: " . base_url."api/Configuracion/Rol/".$api);
            }
            $fechaActual= date('Y-m-d H:i:s'); 
            $NuevaFecha = strtotime ( '+6 hour' , strtotime ($token['fecha_token']) ) ;
            $fecha_token = date ( 'Y-m-d H:i:s' , $NuevaFecha); 
            if($fechaActual>$fecha_token){
                header("location: " . base_url."api/Configuracion/TokenExpirado/".$api);
            }else{
                //actualizar fecha _token
                $token = $this->model->Actualizar_Fecha_token($fechaActual,$api);
            }
        }
    }
    function respuesta($mensaje1,$data=[]){
        $mensaje["mesage"] = $mensaje1;
        $mensaje["data"] = $data;
        return $mensaje;
    }
    public function InUser($token,$parametro)
    {
        $id = $this->model->validarSesionH($token);
        $validacion = $this->model->DatosHistoricos($id['user'],$id['id']);
        $total =[];
        foreach($validacion as $data){
            array_push($total,$this->pulirH($data,$id['gmt']));
        }
        $validacion="List of user actions".$id['user']. " en GMT : ".$id['gmt'];
        echo json_encode($this->respuesta($validacion,$total));

    }
    function pulirH($data,$gmt){
        $array = explode("GMT", $gmt);

        $objeto =[
            "User"=>$data["user"],
            "Action"=>$data["campo"],
            "Previous"=>$data["valor_anterior"],
            "Change"=>$data["valor_modificado"],
            "UserExecuted"=>$data["ejecuto"],
            "Event"=>$data["evento"],
            "Data"=>$this->fechaGmt($data["fecha_evento"],$array[1])
        ];
        return $objeto;
    }
    function fechaGmt($fecha,$gmt){
        $fechaG = 5+($gmt);
        $NuevaFecha = strtotime ( $fechaG.' hour' , strtotime ($fecha) ) ;
        $fecha_token = date ( 'Y-m-d H:i:s' , $NuevaFecha);
        return  $fecha_token;
    }
}
