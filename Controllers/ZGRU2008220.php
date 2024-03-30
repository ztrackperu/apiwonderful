<?php
class ZGRU2008220 extends Controller{
    public function __construct($api){
        parent::__construct();
        $token = $this->model->validar_token($api);
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

    public function Live($token,$parametro){

        $dataGMT= $this->model->getGmt_Temp($token);
        $GMT = $dataGMT['gmt'];
        $GRADO =$dataGMT['modo_temp'];

        $dispositivos = array("ZGRU2008220");
        $mes_fecha = date("n_Y");
        $array = explode(",", $parametro);
        $etiquetas="";
        $etiq=[];
        $data=[];

        if (!empty($array[0])) { if (!empty($array[0] != "")) {$etiquetas = $array[0];}}
        if($etiquetas!=""){
            $arrayE = explode(".", $etiquetas);
            $prueba = encontrarCoincidencias($arrayE);
            if(count($prueba)>0){$etiq=$prueba;}
        }
        for($i=0;$i<count($dispositivos) ;$i++){
            $prueba1 =$dispositivos[$i]."_".$mes_fecha;
            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
            foreach ($cursor as $document) {
            array_unshift($data,objetoW($document,$GMT,$GRADO));
            }
            $validacion= "Last Device Data  : ".$dispositivos[0]." ,with highlighted tags";
            ;
        }
        $datan=[];
        if(count($etiq)>0){
                foreach ($data as $document) {
                    array_unshift($datan,perzonalizarEtiquetas($document,$etiq));
                }
            $data =$datan;
            $validacion= "Last Device Data  : ".$dispositivos[0];
        }

        echo json_encode(respuestaR($validacion,$GMT,$GRADO,$data));  
    }
    public function Total($token,$parametro){
        $dataGMT= $this->model->getGmt_Temp($token);
        $GMT = $dataGMT['gmt'];
        $GRADO =$dataGMT['modo_temp'];
        $array = explode(",", $parametro);
        $cant = 400;
        $etiquetas="";
        $data=[];
        $etiq=[];
        if (!empty($array[0])) { if (!empty($array[0] != "")) {$cant = $array[0];}}
        if (!empty($array[1])) { if (!empty($array[1] != "")) {$etiquetas = $array[1];}}
        if($etiquetas!=""){
            $arrayE = explode(".", $etiquetas);
            $prueba = encontrarCoincidencias($arrayE);
            if(count($prueba)>0){$etiq=$prueba;}
        }
        $validacion =validarCant($cant);
        if($validacion=="ok"){
        $dispositivos = array("ZGRU2008220");
        $mes_fecha = date("n_Y");
        for($i=0;$i<count($dispositivos) ;$i++){
            $prueba1 =$dispositivos[$i]."_".$mes_fecha;
            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>intval($cant)));
            foreach ($cursor as $document) {
                array_unshift($data,objetoW($document,$GMT,$GRADO));
            }
        }
        $validacion= " Device Results   : ".$dispositivos[0]." a total of : ".$cant ."";
        } 
        $datan=[];
	if(count($etiq)>0){
            foreach ($data as $document) {
                array_unshift($datan,perzonalizarEtiquetas($document,$etiq));
            }
	    $data =$datan;
        $validacion= " Device Results   : ".$dispositivos[0]." a total of : ".$cant ." with highlighted tags";
              
	}
        //echo var_dump($etiq);
        echo json_encode(respuestaR($validacion,$GMT,$GRADO,$data));  
    }

    public function Range($token,$parametro){
        $dataGMT= $this->model->getGmt_Temp($token);
        $GMT = $dataGMT['gmt'];
        $GRADO =$dataGMT['modo_temp'];
        $array = explode(",", $parametro);
        $fechaInicio = "";
        $fechaFin="";
        $bd="N";
        $etiquetas="";
	$data = [];
        $dispositivos = array("ZGRU2008220");
        if (!empty($array[0])) { if (!empty($array[0] != "")) {$fechaInicio = $array[0];}}
        if (!empty($array[1])) { if (!empty($array[1] != "")) {$fechaFin = $array[1];}}
        if (!empty($array[2])) { if (!empty($array[2] != "")) {$bd = $array[2];}}
        if (!empty($array[3])) { if (!empty($array[3] != "")) {$etiquetas = $array[3];}}
	//echo $etiquetas;
	$etiqueteOk = [];
	$etique =explode(".",$etiquetas);
	$etiqueteOk = encontrarCoincidencias($etique);
        if($fechaInicio == "" && $fechaFin==""){
          $det = RespuestaUltimo($dispositivos[0],$GMT,$GRADO);
	  $validacion = " last 12 hours ";
          $data =$det; 
        }else{
            $F1=validarFecha($fechaInicio);
            $F2=validarFecha($fechaFin);
            if($F1[0] == "ok"&& $F2[0] == "ok"){
                if(validarBD($bd)=="ok"){
                     //ejecutar consultar por rango con base de datos optimizada 
                     $det =RespuestaRango($dispositivos[0],$F1[1],$F2[1],$bd,$GMT,$GRADO,$etiqueteOk);
                     $validacion =  "Request successfully executed";
		     $data = $det;
                }else{
                      $validacion =  "Incorrect bd format";
                }
            }else{
                $validacion ="Incorrect date format";
            }
        }
        echo json_encode(respuestaR($validacion,$GMT,$GRADO,$data));
    }
}
