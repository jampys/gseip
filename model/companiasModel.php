<?php

class Compania
{
    private $id_compania;
    private $razon_social;
    private $nombre;
    private $cuit;
    private $direccion;
    private $id_localidad;

    // GETTERS
    function getIdCompania()
    { return $this->id_compania;}

    function getRazonSocial()
    { return $this->razon_social;}

    function getNombre()
    { return $this->nombre;}

    function getCuit()
    { return $this->cuit;}

    function getDireccion()
    { return $this->direccion;}

    function getIdLocalidad()
    { return $this->id_localidad;}

    // SETTERS
    function setIdCompania($val)
    { $this->id_compania=$val;}

    function setRazonSocial($val)
    { $this->razon_social=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setCuit($val)
    {  $this->cuit=$val;}

    function setDireccion($val)
    {  $this->direccion=$val;}

    function setIdLocalidad($val)
    { $this->id_localidad=$val;}

    public static function getCompanias() { //ok
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from companias");
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>