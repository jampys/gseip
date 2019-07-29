<?php

class Convenio
{
    private $id_convenio;
    private $codigo;
    private $nombre;

    // GETTERS
    function getIdConvenio()
    { return $this->id_convenio;}

    function getCodigo()
    { return $this->codigo;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdConvenio($val)
    { $this->id_convenio=$val;}

    function setCodigo($val)
    { $this->codigo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getConvenios() { //ok
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from nov_convenios");
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>