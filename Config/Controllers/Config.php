<?php
class Config extends Controller
{
    public function __construct($api){
        parent::__construct();
        //consulta del token de la API con su ultima fecha 
        $token = $this->model->validar_token($api);
        //echo var_dump(($token));     
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
    function generarLetraAleatoria() {
        $numeroAleatorio = rand(0, 25); // Genera un número aleatorio entre 0 y 25
        $letraAleatoria = chr($numeroAleatorio + 97); // Convierte el número a su respectivo carácter ASCII  
        return $letraAleatoria;
    }
    function generar_token($user){
        $fecha=date("Y-m-d H:i:s");
        $NuevaFecha = strtotime($fecha);
        $number = rand(1000,9999);
        $letra = $this->generarLetraAleatoria();
        $calculo= $NuevaFecha*$number;
        $token =$user.$calculo.$letra;
        return $token;

    }
    function respuesta($mensaje1,$data=[]){
        $mensaje["mesage"] = $mensaje1;
        $mensaje["data"] = $data;
        return $mensaje;
    }
    function validar_temp($token,$temp_I){
        $dataTemp= $this->model->getTemp($token);
        if($dataTemp['modo_temp']!=$temp_I){
            if ($temp_I=="F" || $temp_I=="C") {
                $mensaje= "ok";
            }else{ $mensaje= " Incorrect formatting in temp";}
        }else{$mensaje= " Already has the temp set to ".$temp_I;}
        return $mensaje;
    }
    function validar_rol($token,$temp_I){
        $dataTemp= $this->model->getRol($token);
        if($dataTemp['rol']!=$temp_I){
            if ($temp_I==1 || $temp_I==2 || $temp_I==3) {
                $mensaje= "ok";
            }else{ $mensaje= "Incorrect format for Rol";}
        }else{$mensaje= "Has already configured the Role in ".$temp_I;}
        return $mensaje;
    }
    function validar_gmt($token,$gmt_I) {
        $dataGMT= $this->model->getGmt($token);
        if($dataGMT['gmt']!=$gmt_I){
            $array = explode("GMT", $gmt_I);
            $num ="";
            if (!empty($array[1])) {
                if (!empty($array[1] != "")) {
                    $num1 = is_numeric($array[1]);
                    if($num1){
                        $num = $array[1];
                        if($num){
                            if($num >=-12 && $num <= 14){
                                $mensaje="ok";
                            }else{$mensaje=  "GMT out of range";}
                        }else{$mensaje=  "Incorrect format in GMT";}
                    }else{$mensaje=  "Incorrect format in GMT";}
                }
            }else{$mensaje=  "Incorrect format in GMT";}
        }else{$mensaje=  "You already have this GMT set in ".$gmt_I;}
        return $mensaje;
    }
    public function Gmt($token,$param){
        $esquema = explode(",", $param);
        if (!empty($esquema[0])) {
            if ($esquema[0] != "") {
                $validacion =$this->validar_gmt($token,$esquema[0]);
                if($validacion=="ok"){
                    $dataHistorica = $this->model->dataHistorica($token);
                    $dataGMT1= $this->model->cambiarGmt($token, $esquema[0]);
                    if($dataGMT1){
                        $validacion=  " Updated GMT to :".$esquema[0];
                        $GuardarHistorico = $this->model->HistoricoUsuario($dataHistorica['user'],"GMT",$dataHistorica['gmt'],$esquema[0]," NO CONFIDENTIAL",$dataHistorica['user'],$validacion);        
                    }else{$validacion=  "There was an error updating GMT";} 
                }
            }
        }else{$validacion= "I do not enter GMT";}
        echo json_encode($this->respuesta($validacion));
    }
    public function Temp($token,$param){
        $esquema = explode(",", $param);
        if (!empty($esquema[0])) {
            if ($esquema[0] != "") {
                $validacion =$this->validar_temp($token,$esquema[0]);
                if($validacion=="ok"){
                    $dataHistorica = $this->model->dataHistorica($token);
                    $cambiarTemp= $this->model->cambiarTemp($token, $esquema[0]);
                    if($cambiarTemp){
                        $validacion= "Temp was upgraded to : ".$esquema[0];
                        $GuardarHistorico = $this->model->HistoricoUsuario($dataHistorica['user'],"TEMP",$dataHistorica['modo_temp'],$esquema[0]," NO CONFIDENTIAL",$dataHistorica['user'],$validacion);        
                    }else{$validacion= "There was an error updating Temp"; }
                }
            }
        }else{$validacion= "I do not enter the Temperature format";}
        echo json_encode($this->respuesta($validacion));
    }
    public function CreateUser($token,$param)
    {       
        //buscar autorizacion de rol (falta) DE SUPER USUARIO
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['id']==1) {
            $validacion="ok";
            if($param!=""){
                $esquema = explode(",", $param);
                $user = $esquema[0];
                $rol = "1";$gmt = "GMT-8";$temp = "F";       
                if (!empty($esquema[1])) {
                    if (!empty($esquema[1] != "")) { if($esquema[1]==1|| $esquema[1]== 2|| $esquema[1]== 3) {$rol = $esquema[1];
                        }else{$validacion.= "Invalid Role Format ";}
                    }
                }//else{$validacion.= ",No ingreso el Rol ";}
                if (!empty($esquema[2])) {
                    if (!empty($esquema[2] != "")) {
                        $validacion1 =$this->validar_gmt($token,$esquema[2]);
                        if($validacion1=="ok"){ $gmt = $esquema[2];  
                        }else{$validacion=",".$validacion1;}
                    }
                }//else{$validacion.= ",No ingreso el Gmt ";}
                if (!empty($esquema[3])) {
                    if (!empty($esquema[3] != "")) {
                        $validacion1 =$this->validar_temp($token,$esquema[3]);
                        if($validacion1=="ok"){ $temp = $esquema[3];  
                        }else{$validacion=",".$validacion1;}
                    }
                }//else{$validacion.= ",No ingreso el formato de Temperatura ";}
                if($validacion=="ok"){
                    $user_token = $this->generar_token($user);
                    $pass="@".$user;
                    $crearUsuario=$this->model->CreateUser($user_token,$user,$pass,$rol,$gmt,$temp);
                    $GuardarHistorico = $this->model->HistoricoUsuario($user,"NEW","CREATE USER","GMT : ".$gmt." ,Temp : ".$temp. " and Role : ".$rol,"pass:".$pass." and token : ".$user_token,"WONDERFUL",$validacion);        
                    $validacion=$crearUsuario ;
                }
            }else{ $validacion= "Enter the user to create";}
        }else{ $validacion= "You do not have Authorization to create Users";}
        echo json_encode($this->respuesta($validacion));
    }
    //public function Token()
    public function UpdatePassword($token,$param){
        $array = explode(",", $param);
        $passAntiguo = "antiguo";$passNuevo = "nuevo";
        $data=[];
        if (!empty($array[0])) { if (!empty($array[0] != "")) {$passAntiguo = $array[0];}}
        if (!empty($array[1])) {if (!empty($array[1] != "")) {$passNuevo = $array[1];}}
        if($passAntiguo == "antiguo" ){$validacion= "You have not entered your current pass";
        }elseif($passNuevo == "nuevo"){$validacion= "You have not entered your new pass";
        }else{
            $dataHistorica = $this->model->dataHistorica($token);
            $validarUsuario = $this->model->ActulizarPassword($passAntiguo,$passNuevo,$token);
            if($validarUsuario=="ok"){ 
                $validacion= "Pass Successfully updated";    
                $GuardarHistorico = $this->model->HistoricoUsuario($dataHistorica['user'],"PASSWORD",$passAntiguo,"CONFIDENTIAL",$passNuevo,$dataHistorica['user'],$validacion);                 
            }else{$validacion=$validarUsuario;}
        }
        echo json_encode($this->respuesta($validacion,$data));  
    }
    public function MyConfig($token,$param){
        $data=[];
        $validarUsuario = $this->model->MyConfig($token);
        if($validarUsuario){
            $data['User'] =$validarUsuario['user'];
            $data['GMT'] =$validarUsuario['gmt'];
            $data['Temperature'] =$validarUsuario['modo_temp'];
            if($validarUsuario['rol']=="1"){$rol = "MONITORING";}
            if($validarUsuario['rol']=="2"){$rol = "MONITORING AND CONTROL";}
            if($validarUsuario['rol']=="3"){$rol = "MONITORING , CONTROL AND  HISTORY";}
            $data['Role'] =$rol;
            $validacion = "Solicitud exitosa";
        }else{$validacion=$validarUsuario;}
        echo json_encode($this->respuesta($validacion,$data));  
    }
    public function Update($token,$param){
        $superUsuario = $this->model->superUser($token);
        if ($superUsuario['id']==1) {
            //$validacion ="estas autorizado a manipular";
            $array = explode(",", $param);
            $userM = "user";$accion = "accion";$param ="";
            if (!empty($array[0])) { if (!empty($array[0] != "")) {$userM = $array[0];}}
            if (!empty($array[1])) {if (!empty($array[1] != "")) {$accion = $array[1];}}
            if (!empty($array[2])) {if (!empty($array[2] != "")) {$param = $array[2];}}
            if($userM == "user" ){$validacion= "No user login for intervention";
            }elseif($accion == "accion"){$validacion= "No action has been filed";
            }elseif($param == "" &&(strtolower($accion)=="gmt"||strtolower($accion)=="temp"||strtolower($accion)=="rol")){$validacion= "You have not entered the data to be updated";
            }else{
                //$validacion= "ramo bien";             
                $validarUsuario = $this->model->exiteUsuario($userM);
                if($validarUsuario){
                    //$validacion= "Usuario si existe";
                    if(strtolower($accion)== "disabled"||strtolower($accion)== "enabled"||strtolower($accion)== "resetpassword" ||strtolower($accion)== "gmt"||strtolower($accion)== "rol"||strtolower($accion)== "temp"){
                        //$validacion= "Accion si disponible";
                        if(strtolower($accion)== "gmt"){
                            $validacion =$this->validar_gmt($validarUsuario['token'],$param);
                            if($validacion=="ok"){
                                $dataGMT1= $this->model->cambiarGmt($validarUsuario['token'], $param);
                                if($dataGMT1){
                                    $validacion=  " Updated GMT to :".$param;
                                    $GuardarHistorico = $this->model->HistoricoUsuario($validarUsuario['user'],"GMT",$validarUsuario['gmt'],$param," NO CONFIDENTIAL","WONDERFUL",$validacion);        
                                }else{$validacion=  "There was an error updating GMT";} }
                        }
                        if(strtolower($accion)== "temp"){
                            $validacion =$this->validar_temp($validarUsuario['token'],$param);
                            if($validacion=="ok"){
                                $cambiarTemp= $this->model->cambiarTemp($validarUsuario['token'], $param);
                                if($cambiarTemp){
                                    $validacion= "There was an error updating Temp :$param";
                                    $GuardarHistorico = $this->model->HistoricoUsuario($validarUsuario['user'],"TEMP",$validarUsuario['modo_temp'],$param," NO CONFIDENTIAL","WONDERFUL",$validacion);        
                                }else{$validacion= "I do not enter the Temperature format"; }}
                        }
                        if(strtolower($accion)== "rol"){
                            $validacion =$this->validar_rol($validarUsuario['token'],$param);
                            if($validacion=="ok"){
                                $cambiarRol= $this->model->cambiarRol($validarUsuario['token'], $param);
                                if($cambiarRol){
                                    $validacion= "Role was upgraded to :$param";
                                    $GuardarHistorico = $this->model->HistoricoUsuario($validarUsuario['user'],"ROLE",$validarUsuario['rol'],$param," NO CONFIDENTIAL","WONDERFUL",$validacion);        
                                }else{$validacion= "there was an error updating Role"; }}
                        }
                        if(strtolower($accion)== "resetpassword"){
                            $Rpassword = "@".$userM ;
                            $ResetPass= $this->model->resetPass($validarUsuario['token'], $Rpassword);
                            if($ResetPass=="ok"){
                                $validacion= " Successful upgrade Reset Pass to :$Rpassword";
                                $GuardarHistorico = $this->model->HistoricoUsuario($validarUsuario['user'],"PASSWORD",$validarUsuario['pass'],"CONFIDENTIAL",$Rpassword,"WONDERFUL",$validacion);        
                            }else{$validacion= $ResetPass; }
                        }
                        if(strtolower($accion)== "disabled"){
                            if($validarUsuario['estado']==1){
                                //$validacion= "se procede a Bloquear Usuario";
                                $bloquearUsuario= $this->model->bloquearUsuario($validarUsuario['token']);
                                if($bloquearUsuario=="ok"){
                                    $validacion= "Succeeded in blocking the user :".$userM;
                                    $GuardarHistorico = $this->model->HistoricoUsuario($validarUsuario['user'],"STATUS","ASSET","BLOCKED","NO CONFIDENTIAL","WONDERFUL",$validacion);        
                                }else{$validacion=$bloquearUsuario ; }
                            }else{$validacion= "User is already blocked"; }                  
                        }
                        if(strtolower($accion)== "enabled"){
                            if($validarUsuario['estado']==0){
                                //$validacion= "se procede a Activar Usuario";
                                $activarUsuario= $this->model->activarUsuario($validarUsuario['token']);
                                if($activarUsuario=="ok"){
                                    $validacion= "The user has been activated :".$userM;
                                    $GuardarHistorico = $this->model->HistoricoUsuario($validarUsuario['user'],"STATUS","BLOCKED","ASSET","NO CONFIDENTIAL","WONDERFUL",$validacion);        
                                }else{$validacion=$activarUsuario ; }
                            }else{$validacion= " User is already Active"; }                  
                        }
                    }else{
                        $validacion= "No action available";
                    }
                }else{
                    $validacion= "User does not exist";  
                }
            }
        }else{ $validacion= "You do not have Administrator Authorization";}
        echo json_encode($this->respuesta($validacion));
    }
    

}