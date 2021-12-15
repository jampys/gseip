<?php

class Modalidad
{
    private $id_modalidad;
    private $nombre;

    // GETTERS
    function getIdModalidad()
    { return $this->id_modalidad;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdModalidad($val)
    { $this->id_modalidad=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getModalidades() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from cap_modalidades");
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>