<?php

class VencimientoPersonal
{
    private $id_vencimiento;
    private $nombre;


    // GETTERS
    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdVencimiento($val)
    { $this->id_vencimiento=$val;}

    function setNombre($val)
    { $this->nombre=$val;}



    public static function getVencimientosPersonal() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from vto_vencimiento_p");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los vencimientos de personal
    }



}


?>