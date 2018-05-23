<?php

class EventosLiquidacion
{
    private $id_evento;
    private $codigo;
    private $nombre;
    private $descripcion;

    // GETTERS
    function getIdEvento()
    { return $this->id_evento;}

    function getCodigo()
    { return $this->codigo;}

    function getNombre()
    { return $this->nombre;}

    function getDescripcion()
    { return $this->descripcion;}


    // SETTERS
    function setIdEvento($val)
    { $this->id_evento=$val;}

    function setCodigo($val)
    { $this->codigo=$val;}

    function setNombre($val)
    {  $this->nombre=$val;}

    function setDescripcion($val)
    {  $this->descripcion=$val;}


    public static function getEventosLiquidacion() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from nov_eventos_l");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los eventos de liquidacion
    }



}


?>