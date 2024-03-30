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
        $mensaje["mesage"] = "Request not found ";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }
    public function NoAutorizado($param)
    {
        $mensaje =[];
        $mensaje["mesage"] = "You are not authorized ";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }
    public function Inactivo($param)
    {
       // $this->views->getView($this, "error");
       $mensaje =[];
       $mensaje["mesage"] = "You are not enabled, please contact the administrator.";
       $mensaje["data"] = "";
       echo json_encode($mensaje);
    }
    public function TokenExpirado($param)
    {
        $mensaje["mesage"] = "The token is expired, generate a new one.";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }
    public function Rol($param)
    {
        $mensaje["mesage"] = "You don't have the right role";
        $mensaje["data"] = "";
        echo json_encode($mensaje);
    }

}