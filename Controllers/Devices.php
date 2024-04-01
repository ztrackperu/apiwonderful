<?php

class Devices extends Controller
{
    
    public function __construct($api){
        parent::__construct();
        //consulta del token de la API con su ultima fecha 
        $token = $this->model->validar_token($api);
        echo var_dump(($token));     
        if($token==false){
            header("location: " . base_url."api/Configuracion/NoAutorizado/".$api);
        }else{
            if($token['estado']!=1){
                header("location: " . base_url."api/Configuracion/Inactivo/".$api);
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



    public function Live($token,$param)
    {
        //consultar a la base de Datos 
        $dataGMT= $this->model->getGmt_Temp($token);
        echo var_dump(json_encode($dataGMT));
        if($dataGMT){
            $GMT = $dataGMT['gmt'];
            $GRADO =$dataGMT['modo_temp'];
            $dispositivos = array("ZGRU1090804","ZGRU2232647","ZGRU2009227","ZGRU2008220");
            $mes_fecha = date("n_Y");
            for($i=0;$i<count($dispositivos) ;$i++){
              $prueba1 =$dispositivos[$i]."_".$mes_fecha;
              $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
              $total[$dispositivos[$i]]=[];
              foreach ($cursor as $document) {
                array_unshift($total[$dispositivos[$i]],objetoW($document,$GMT,$GRADO));
              }
            }
            echo json_encode($total);
            echo  " JEJEJ";
        }else{
            header("location: " . base_url."api/Configuracion/NoAutorizado/".$token);
 
        }

     }
   
 }
       
?>
       
