<?php
class Views{
//$this->views->getView(Configuracion, "error");
    public function getView($controlador, $vista, $data="")
    {

        $controlador = get_class($controlador);
        //$controlador = $controlador;
        //se va al login 
        if ($controlador == "Home" || $controlador == "Principal") {
            $vista = "Views/".$vista.".php";
        }  
         
        else{
            $vista = "Views/".$controlador."/".$vista.".php";
        }
        require $vista;
    }
}


?>