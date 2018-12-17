<?php

class Localidad
{
    private $id_localidad;
    private $ciudad;
    private $cp;
    private $provincia;
    private $pais;

    // GETTERS
    function getIdLocalidad()
    { return $this->id_localidad;}

    function getCiudad()
    { return $this->ciudad;}

    function getCp()
    { return $this->cp;}

    function getProvincia()
    { return $this->provincia;}

    function getPais()
    { return $this->pais;}

    // SETTERS
    function setIdLocalidad($val)
    { $this->id_localidad=$val;}

    function setCiudad($val)
    { $this->ciudad=$val;}

    function setCp($val)
    {  $this->cp=$val;}

    function setProvincia($val)
    {  $this->provincia=$val;}

    function setPais($val)
    {  $this->pais=$val;}

    public static function getLocalidades() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from localidades");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas las localidades
    }

    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from localidades
                    where id_localidad = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdLocalidad($rows[0]['id_localidad']);
            $this->setCiudad($rows[0]['ciudad']);
            $this->setCp($rows[0]['CP']);
            $this->setProvincia($rows[0]['provincia']);
            $this->setPais($rows[0]['pais']);
        }
    }



}


?>