<?php

class NovArea
{
    private $id_area;
    private $codigo;
    private $nombre;

    // GETTERS
    function getIdArea()
    { return $this->id_area;}

    function getCodigo()
    { return $this->codigo;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdArea($val)
    { $this->id_area=$val;}

    function setCodigo($val)
    { $this->codigo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getAreas() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from nov_areas");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas las areas
    }



}


?>