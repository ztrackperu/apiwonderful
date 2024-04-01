<?php
class Login extends Controller
{
    public function __construct($api)
    {
        parent::__construct();
        //token es "wonderful?123"
        if($api!="Api"){
            header("location: " . base_url."api/Configuracion/NoAutorizado/".$api);
        }
        
        //echo $api;
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
        $mensaje["message"] = $mensaje1;
        $mensaje["data"] = $data;
        return $mensaje;
    }
    public function Token($token,$parametro)
    {
        $array = explode(",", $parametro);
        $user = "usuario";
        $pass = "password";
        $data=[];
        if (!empty($array[0])) {
            if (!empty($array[0] != "")) {$user = $array[0];}
        }
        if (!empty($array[1])) {
            if (!empty($array[1] != "")) {$pass = $array[1];}
        }
        if($user == "usuario" ){
            $validacion= "Not logged in User";
        }elseif($pass == "password"){
            $validacion= "Password not entered";
        }else{
            //validar user y password 
            $validarUsuario = $this->model->validarSesion($user,$pass);
            if($validarUsuario){
                if($validarUsuario["estado"]==1){
                    $fechaActual= date('Y-m-d H:i:s'); 
                    $NuevaFecha = strtotime ( '+6 hour' , strtotime ($validarUsuario['fecha_token']) ) ;
                    $fecha_token = date ( 'Y-m-d H:i:s' , $NuevaFecha); 
                    if($fechaActual>$fecha_token){
                        $user_token = $this->generar_token($user);
                        $ActualizarToken = $this->model->ActualizarToken($user_token,$user,$pass);
                        if($ActualizarToken=="ok"){
                            $validacion="TOKEN GENERATED";
                            $GuardarHistorico = $this->model->HistoricoUsuario($user,"TOKEN",$validarUsuario['token'],"CONFIDENTIAL",$user_token,$user,$validacion);        
                            $data=["token"=>$user_token];
                        }else{
                            $validacion=$ActualizarToken;
                        } 
                    }else{
                        $validacion="Has an Active Token";
                        $data=["token"=>$validarUsuario["token"]];
                    }
                }else{
                    $validacion= "User Disabled, please contact administrator";   
                }
            }else{
                $validacion= "Incorrect User or Password";   
            }
        }
        echo json_encode($this->respuesta($validacion,$data));      
    }


}
