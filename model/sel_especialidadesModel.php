<?php

class Especialidad
{
    private $id_especialidad;
    private $nombre;

    // GETTERS
    function getIdEspecialidad()
    { return $this->id_especialidad;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdEspecialidad($val)
    { $this->id_especialidad=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getEspecialidades() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from sel_especialidades");
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>