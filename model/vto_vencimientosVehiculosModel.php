<?php

class VencimientoVehicular
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



    public static function getVencimientosVehiculos() { //ok
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from vto_vencimiento_v
                          order by nombre");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los vencimientos de vehiculos
    }




}


?>