<?php
class Usuarios extends Controller{
    public function __construct() {
        session_start();
        parent::__construct();
    }

    public function validar()
    {
        $usuario = strClean($_POST['usuario']);
        $clave = strClean($_POST['clave']);
        if (empty($usuario) || empty($clave)) {
            $msg = array('msg' => 'Todo los campos son requeridos', 'icono' => 'warning');
        }else{
            //$hash = hash("SHA256", $clave);
            $hash = $clave;
            $data = $this->model->getUsuario($usuario, $hash);
            if ($data) {
                /*
                $_SESSION['id_usuario'] = $data['id'];
                $_SESSION['usuario'] = $data['usuario'];
                $_SESSION['nombre'] = $data['nombre'];
                $_SESSION['activo'] = true;
                */
                $msg = array('msg' => 'Procesando', 'icono' => 'success');
            }else{
                $msg = array('msg' => 'Usuario o contraseña incorrecta', 'icono' => 'warning');
            }
        }
        echo json_encode($msg, JSON_UNESCAPED_UNICODE);
        die();
    }




}