<?php

class Subcontratista
{
    private $id_subcontratista;
    private $nombre;
    private $razon_social;
    private $cuit;

    // GETTERS
    function getIdSubcontratista()
    { return $this->id_subcontratista;}

    function getNombre()
    { return $this->nombre;}

    function getRazonSocial()
    { return $this->razon_social;}

    function getCuit()
    { return $this->cuit;}

    // SETTERS
    function setIdSubcontratista($val)
    { $this->id_subcontratista=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setRazonSocial($val)
    {  $this->razon_social=$val;}

    function setCuit($val)
    {  $this->cuit=$val;}


    public static function getSubcontratistas() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select *
                          from subcontratistas su
                          join companias co on su.id_compania = co.id_compania
                          where su.fecha_baja is null
                          order by razon_social asc");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los subcontratistas
    }



}


?>