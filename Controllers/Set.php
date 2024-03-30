<?php
include "funciones.php";
class Set extends Controller
{
    public function __construct($api)
    {
        parent::__construct();
        //token es "wonderful?123"
        if($api!="wonderful_123"){
            header("location: " . base_url."api/Configuracion/NoAutorizado/".$api);
        }
        //echo $api;
    }
    public function Live($param)
    {
         //desarmar el valor de parametro
        $datazo = explode("/", $param);
        $GMT = "GMT-8";
        $GRADO ="C";
        if (!empty($datazo[0])) {
            if (!empty($datazo[0] != "")) {
                $GMT = $datazo[0];
            }
        }
        if (!empty($datazo[1])) {
            if (!empty($datazo[1] != "")) {
                $GRADO = $datazo[1];
            }
        }
        $Dispositivos =["ZGRU1090804","ZGRU2232647","ZGRU2009227","ZGRU2008220"];
        //desarmar el valor de parametro
        $HOY =date("n_Y");

        //$data1 = json_decode($resultadoEX);
        //echo $resultadoEX;
        echo "oli";
    }

}