<?php
class Total extends Controller
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
    public function index()
    {
        //$this->views->getView($this, "index");
        //echo "usted esta autorizado !";
        $url1 = "http://161.132.206.104/wonderful/?pas=@wonderful?2024";
        $sql =1;
        $datos = [
            "sql" => $sql,
        ];
        $opciones = array(
            "http" => array(
                "header" => "Content-type: application/json\r\n",
                "method" => "POST",
                "content" => json_encode($datos), # Agregar el contenido definido antes
            ),
        );
        # Preparar petición
        $contexto = stream_context_create($opciones);
        $resultadoEX = file_get_contents($url1, false, $contexto);
        //$data1 = json_decode($data);
        $data1 = json_decode($resultadoEX);
        echo $resultadoEX;
    }

}