<?php
class ZGRU1090804 extends Controller{
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

        $dispositivos = array("ZGRU1090804");
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
        $dispositivos = array("ZGRU1090804");
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

        $dispositivos = array("ZGRU1090804");
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
	$db1=$bd ;
            $F1=validarFecha($fechaInicio);
            $F2=validarFecha($fechaFin);
            if($F1[0] == "ok"&& $F2[0] == "ok"){
                if(validarBD($bd)=="ok"){
                     //ejecutar consultar por rango con base de datos optimizada 
                     $det =RespuestaRango($dispositivos[0],$F1[1],$F2[1],$bd,$GMT,$GRADO,$etiqueteOk);
                     $validacion =  "Request successfully executed ".$db1;
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
    function respuesta($mensaje1,$data=[]){
        $mensaje["message"] = $mensaje1;
        $mensaje["data"] = $data;
        return $mensaje;
    }

    public function ControlHumidity($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable="";
            $clave="";
            $array = explode(",", $parametro);
            $dispositivos = array("ZGRU1090804");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$variable = $array[0];}}
            if (!empty($array[1])) { if (!empty($array[1] != "")) {$clave = $array[1];}}        
            if ( $variable!="") {
                if ($clave!="") {
                    //validacion de humedad de 0 a 99
                    if ($variable>=50 && $variable<= 99) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              $Gcomando = $this->model->guardarComando($dispositivos[0],8,NA($resul[0]["TelematicId"]),NA($resul[0]["HumiditySetPoint"]),intVal($variable) ,$superUsuario['id']);
                               $validacion =$Gcomando;
                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "Humidity parameter out of range";}
                }else{$validacion= "You did not enter the password";}
            }else{$validacion= "You did not enter the humidity parameter";}
            //$validacion ="vamos ok";

            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }
    public function ControlTemperature($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable="";
            $clave="";
            $array = explode(",", $parametro);
            $dispositivos = array("ZGRU1090804");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$variable = $array[0];}}
            if (!empty($array[1])) { if (!empty($array[1] != "")) {$clave = $array[1];}}        
            if ( $variable!="") {
                if ($clave!="") {
                    //validacion de humedad de 0 a 99
                    if($superUsuario['modo_temp']=="F")   {
                        $variable = round(($variable-32)*(5/9),1);
                    }  
                    if ($variable>=-40 && $variable<= 30) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              $Gcomando = $this->model->guardarComando($dispositivos[0],4,NA($resul[0]["TelematicId"]),NA($resul[0]["TempSetPoint"]),$variable ,$superUsuario['id']);
                               $validacion =$Gcomando;
                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "Temperature parameter out of range ";}
                }else{$validacion= "You did not enter the password";}
            }else{$validacion= "You did not enter the temperature parameter";}
            //$validacion ="vamos ok";

            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }
    public function ControlEthylene($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable="";
            $clave="";
            $array = explode(",", $parametro);
            $dispositivos = array("ZGRU1090804");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$variable = $array[0];}}
            if (!empty($array[1])) { if (!empty($array[1] != "")) {$clave = $array[1];}}        
            if ( $variable!="") {
                if ($clave!="") {
                    if ($variable>= 0 && $variable<= 240) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              if(intVal($variable)==6){
                                $validacion= "The change to 6 ppm is restricted";
                              }else{
                                $Gcomando = $this->model->guardarComando($dispositivos[0],6,NA($resul[0]["TelematicId"]),NA($resul[0]["EthyleneSetPoint"]),intVal($variable ),$superUsuario['id']);
                                $validacion =$Gcomando;
                              }
                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "Ethylene parameter out of range";}
                }else{$validacion= "You did not enter the password";}
            }else{$validacion= "You did not enter the ethylene parameter ";}
            //$validacion ="vamos ok";

            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }
    public function ControlCo2($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable="";
            $clave="";
            $array = explode(",", $parametro);
            $dispositivos = array("ZGRU1090804");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$variable = $array[0];}}
            if (!empty($array[1])) { if (!empty($array[1] != "")) {$clave = $array[1];}}        
            if ( $variable!="") {
                if ($clave!="") {
                    if ($variable>= 0 && $variable<= 20) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              //$Gcomando = $this->model->guardarComando($dispositivos[0],7,NA($resul[0]["TelematicId"]),NA($resul[0]["SetPointCo2"]),$variable ,$superUsuario['id']);
                               //$validacion =$Gcomando;
                               $validacion= "Software Update Required";
                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "CO2 parameter out of range";}
                }else{$validacion= "You did not enter the password";}
            }else{$validacion= "You did not enter the CO2 parameter";}
            //$validacion ="vamos ok";

            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }
    public function Defrost($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable=4;
            $clave="";
            $array = explode(",", $parametro);
            //$dispositivos = array("ZGRU2009227");
            $dispositivos = array("ZGRU1090804");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$clave = $array[0];}}               
                if ($clave!="") {
                    if ($variable>= 0 && $variable<= 4) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              if($resul[0]["PowerState"]==1){
                                //$Gcomando = $this->model->guardarComando($dispositivos[0],10,NA($resul[0]["TelematicId"]),$variable,NA($resul[0]["PowerState"]-1),$superUsuario['id']);
                                //$validacion =$Gcomando;
                                $validacion= "Software Update Required";
                              }else{
                                $validacion ="The device is turned off, you must turn it on to perform the defrost";
                              }

                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "Defrost parameter out of range";}
                }else{$validacion= "You did not enter the password";}  
            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }

    public function TurnOn($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable=1;
            $clave="";
            $array = explode(",", $parametro);
            $dispositivos = array("ZGRU1090804");
            //$dispositivos = array("ZGRU1268663");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$clave = $array[0];}}               
                if ($clave!="") {
                    if ($variable>= 0 && $variable<= 1) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              if($resul[0]["PowerState"]==0){
                                //$Gcomando = $this->model->guardarComando($dispositivos[0],3,NA($resul[0]["TelematicId"]),NA($resul[0]["PowerState"]),$variable ,$superUsuario['id']);
                                //$validacion =$Gcomando;
                                $validacion= "Software Update Required";

                              }else{
                                $validacion ="The device is already turned on ";
                              }

                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "TurnOn parameter out of range";}
                }else{$validacion= "You did not enter the password";}  
            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }
    public function TurnOff($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable=0;
            $clave="";
            $array = explode(",", $parametro);
            $dispositivos = array("ZGRU1090804");
            //$dispositivos = array("ZGRU1268663");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$clave = $array[0];}}               
                if ($clave!="") {
                    if ($variable>= 0 && $variable<= 1) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              if($resul[0]["PowerState"]==1){
                                //$Gcomando = $this->model->guardarComando($dispositivos[0],9,NA($resul[0]["TelematicId"]),NA($resul[0]["PowerState"]),$variable ,$superUsuario['id']);
                                //$validacion =$Gcomando;
                                $validacion= "Software Update Required";
                              }else{
                                $validacion ="The device is already turned off ";
                              }

                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "TurnOFF parameter out of range";}
                }else{$validacion= "You did not enter the password";}  
            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }
    public function Commands($token,$parametro){
        //verificar que tenga el rol 2 o 3 sino rechazar por permiso
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['rol']==2 ||$superUsuario['rol']==3) {        
            $variable=0;
            $clave="";
            $array = explode(",", $parametro);
            $dispositivos = array("ZGRU1090804");
            //$dispositivos = array("ZGRU1268663");
            $resul =[];
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$clave = $array[0];}}               
                if ($clave!="") {
                    if ($variable>= 0 && $variable<= 1) {
                        if($superUsuario['pass']==$clave){
                            //validaciones correctas , insertar en tabla comandos
                            $mes_fecha = date("n_Y");
                            $prueba1 =$dispositivos[0]."_".$mes_fecha;
                            $cursor  = client->$prueba1->madurador->find(array(),array('sort'=>array('id'=>-1),'limit'=>1));
                            foreach ($cursor as $document) {
                                array_push($resul,objetoW($document,$superUsuario['gmt'],$superUsuario['modo_temp']));
                              }
                              //$validacion =" la telemetria es :".NA($resul[0]["TelematicId"])." y la data del set de humedad :".NA($resul[0]["HumiditySetPoint"]). "y el valor actual es :".NA($resul[0]["RelativeHumidity"]);
                              //guardar en comandos 
                              if($resul[0]["PowerState"]==1){
                                $Gcomando = $this->model->guardarComando($dispositivos[0],9,NA($resul[0]["TelematicId"]),NA($resul[0]["PowerState"]),$variable ,$superUsuario['id']);
                                $validacion =$Gcomando;
                              }else{
                                $validacion ="The device is already turned off ";
                              }

                        }else{$validacion= "Incorrect password";}
                    }else{$validacion= "TurnOn parameter out of range";}
                }else{$validacion= "You did not enter the password";}  
            //verificar ocntrseña y dato que se numerico y que este un rango adecuado
        }else{ $validacion= "You do not have Authorization :(";}
        echo json_encode($this->respuesta($validacion));
    }


    
}
