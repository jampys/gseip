<?php

class EventosCuadrilla
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


    public static function getEventosCuadrilla() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from nov_eventos_c");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los eventos de liquidacion
    }



}


?>