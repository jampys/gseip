<?php

class Area
{
    private $id_area;
    private $nombre;

    // GETTERS
    function getIdArea()
    { return $this->id_area;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdArea($val)
    { $this->id_area=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getAreas() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from areas");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas las areas
    }



}


?>