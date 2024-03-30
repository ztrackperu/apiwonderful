<?php
class PrincipalModel extends Query{

    public function __construct()
    {
        parent::__construct();
    }
    public function getContenedores()
    {
        $sql = "SELECT * FROM contenedores order by id limit 11";
        $data = $this->selectAll($sql);
        return $data;
    }

}