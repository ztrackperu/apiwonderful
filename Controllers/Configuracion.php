<?php
class Configuracion extends Controller
{
    public function __construct()
    {
        /*
        session_start();
        if (empty($_SESSION['activo'])) {
            header("location: " . base_url);
        }
        */
        parent::__construct();
        
    }
    public function error()
    {
        $mensaje =[];
        $mensaje["message"] = "Request not found ";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }
    public function NoAutorizado($param)
    {
        $mensaje =[];
        $mensaje["message"] = "You are not authorized ";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }
    public function Inactivo($param)
    {
       // $this->views->getView($this, "error");
       $mensaje =[];
       $mensaje["message"] = "You are not enabled, please contact the administrator.";
       $mensaje["data"] = "";
       echo json_encode($mensaje);
    }
    public function TokenExpirado($param)
    {
        $mensaje["message"] = "The token is expired, generate a new one.";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }
    public function Rol($param)
    {
        $mensaje["message"] = "You don't have the right role";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }

}