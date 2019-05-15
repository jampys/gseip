<?php

class NovPeriodo
{
    private $id_periodo;
    private $nombre;
    private $id_contrato;
    private $fecha_desde;
    private $fecha_hasta;
    private $createdDate;
    private $closedDate;

    // GETTERS
    function getIdPeriodo()
    { return $this->id_periodo;}

    function getNombre()
    { return $this->nombre;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getCreatedDate()
    { return $this->createdDate;}

    function getClosedDate()
    { return $this->closedDate;}


    // SETTERS
    function setIdPeriodo($val)
    { $this->id_periodo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setFechaDesde($val)
    { $this->fecha_desde=$val;}

    function setFechaHasta($val)
    { $this->fecha_hasta=$val;}

    function setCreatedDate($val)
    { $this->createdDate=$val;}

    function setClosedDate($val)
    { $this->closedDate=$val;}


    public static function getPeriodosActivos($id_contrato = null) {
        //si tiene id_contrato como parametro, filtra por parametro. Sino trae todos.
        $stmt=new sQuery();
        $query="select pe.id_periodo, pe.nombre, pe.id_contrato,
                DATE_FORMAT(pe.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                DATE_FORMAT(pe.fecha_hasta,  '%d/%m/%Y') as fecha_hasta
                from nov_periodos pe
                where pe.id_contrato = ifnull(:id_contrato, pe.id_contrato)
                and pe.closed_date is null
                order by pe.fecha_desde asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los periodos
    }



}


?>