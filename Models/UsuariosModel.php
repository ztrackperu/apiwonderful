<?php
class UsuariosModel extends Query{
    private $usuario, $nombre, $clave, $id, $estado;
    public function __construct()
    {
        parent::__construct();
    }
    public function getUsuario($usuario, $clave)
    {
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave' AND estado = 1";
        $data = $this->select($sql);
        return $data;
    }

}