<?php

class Calendar
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


    public static function getFeriados() {
        $stmt=new sQuery();
        $query = "select id_etapa, id_postulacion,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      DATE_FORMAT(fecha_etapa, '%d/%m/%Y') as fecha_etapa,
                      etapa, aplica, motivo, modo_contacto, comentarios, id_user
                      from sel_etapas
                      where id_etapa = :nro";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas las areas
    }



}


?>