<?php

class Proceso
{
    private $id_proceso;
    private $codigo;
    private $nombre;

    // GETTERS
    function getIdProceso()
    { return $this->id_proceso;}

    function getCodigo()
    { return $this->codigo;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdProceso($val)
    { $this->id_proceso=$val;}

    function setCodigo($val)
    { $this->codigo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getProcesos() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from procesos");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas los procesos
    }



}


?>