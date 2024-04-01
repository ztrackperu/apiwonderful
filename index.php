<?php
//ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ob_end_flush();
$dominioPermitido = "*";
header("Access-Control-Allow-Origin: $dominioPermitido");
header("Access-Control-Allow-Headers: content-type");
header("Access-Control-Allow-Methods: OPTIONS,GET,PUT,POST,DELETE");
header('Content-type: application/json; charset=utf-8');

require_once "Config/Config.php";
require_once "Config/Helpers.php";
require_once "Config/funciones.php";
require '../../test/ztotal/vendor/autoload.php';
//require '../ztotal/vendor/autoload.php';
//use Exception;
use MongoDB\Client;
use MongoDB\Driver\ServerApi;
use MongoDB\BSON\UTCDateTime ;
const uri = 'mongodb://localhost:27017';
// Specify Stable API version 1
const apiVersion = new ServerApi(ServerApi::V1);
// Create a new client and connect to the server
const client = new MongoDB\Client(uri, [], ['serverApi' => apiVersion]);
try {
    // Send a ping to confirm a successful connection
    client->selectDatabase('ZTRACK_P')->command(['ping' => 1]);
    //echo "Pinged your deployment. You successfully connected to MongoDB!\n";
} catch (Exception $e) {
    printf($e->getMessage());
}


    date_default_timezone_set('America/Lima');
    $ruta = !empty($_GET['url']) ? $_GET['url'] : "Api/Configuracion/Error";
    $array = explode("/", $ruta);
    $token = $array[0];
    $controller = "Devices";
    $metodo = "Live";
    $parametro = "";
    if (!empty($array[1])) {
        if (!empty($array[1] != "")) {
            $controller = $array[1];
        }
    }
    if (!empty($array[2])) {
        if (!empty($array[2] != "")) {
            $metodo = $array[2];
        }
    }
    if (!empty($array[3])) {
        if (!empty($array[3] != "")) {
            for ($i=3; $i < count($array); $i++) { 
                $parametro .= $array[$i]. ",";
            }
            $parametro = trim($parametro, ",");
        }
    }
    require_once "Config/App/Autoload.php";
    $dirControllers = "Controllers/".$controller.".php";
    if (file_exists($dirControllers)) {
        require_once $dirControllers;
        $controller = new $controller($token);
        if (method_exists($controller, $metodo)) {
            $controller->$metodo($token,$parametro);
        }else{
            header('Location:' . base_url . 'Api/Configuracion/Error');
        }
    }else{
        header('Location:' . base_url . 'Api/Configuracion/Error');
    }

?>
