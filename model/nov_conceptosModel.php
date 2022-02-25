<?php

class Concepto
{
    private $id_concepto;
    private $nombre;

    // GETTERS
    function getIdConcepto()
    { return $this->id_concepto;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdConcepto($val)
    { $this->id_concepto=$val;}


    function setNombre($val)
    { $this->nombre=$val;}


    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select *
                    from nov_conceptos
                    where id_concepto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdConcepto($rows[0]['id_concepto']);
            $this->setNombre($rows[0]['nombre']);
        }
    }



}


?>