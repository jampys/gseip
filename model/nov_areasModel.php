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


    public static function getAreas($id_contrato = null) {
        //si tiene id_contrato como parametro, filtra por parametro. Sino trae todos.
        $stmt=new sQuery();
        $query="select ar.*
                from nov_areas ar
                join nov_area_values nav on nav.id_area = ar.id_area
                where nav.id_contrato = ifnull(:id_contrato, nav.id_contrato)
                group by ar.id_area
                order by ar.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas las areas
    }



}


?>