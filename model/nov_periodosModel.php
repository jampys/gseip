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


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_periodo, nombre, id_contrato,
                      DATE_FORMAT(fecha_desde, '%d/%m/%Y') as fecha_desde,
                      DATE_FORMAT(fecha_hasta, '%d/%m/%Y') as fecha_hasta,
                      created_date, closed_date
                      from nov_periodos
                      where id_periodo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdPeriodo($rows[0]['id_periodo']);
            $this->setNombre($rows[0]['nombre']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setCreatedDate($rows[0]['created_date']);
            $this->setClosedDate($rows[0]['closed_date']);
        }
    }


    public static function getPeriodos($id_contrato = null, $activos = null) {
        //Trae los periodos por contrato
        //si se llama sin parametro, trae solo los periodos activos
        $stmt=new sQuery();
        $query="select pe.id_periodo, pe.nombre, pe.id_contrato,
                DATE_FORMAT(pe.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                DATE_FORMAT(pe.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                pe.closed_date
                from nov_periodos pe
                where pe.id_contrato = ifnull(:id_contrato, pe.id_contrato)
                and if(:activos is null, 1, pe.closed_date is null)
                order by pe.fecha_desde desc";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':activos', $activos);
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los periodos
    }


    public static function getPeriodos1($id_empleado, $activos = null) {
        //Trae los periodos de todos los contratos donde esta el empleado
        $stmt=new sQuery();
        $query="select pe.id_periodo, pe.nombre,
DATE_FORMAT(pe.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(pe.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
pe.closed_date,
pe.id_contrato, co.nombre as contrato
from empleados em
join empleado_contrato ec on ec.id_empleado = em.id_empleado
join v_sec_contratos_control co on co.id_contrato = ec.id_contrato
join nov_periodos pe on pe.id_contrato = co.id_contrato
where em.id_empleado = :id_empleado
and if(:activos is null, 1, pe.closed_date is null)
order by pe.fecha_desde desc";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':activos', $activos);
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los periodos
    }


    public static function getPeriodosSup() {
        //Trae todos los periodos superiores
        $stmt=new sQuery();
        $query="select periodo, SUBSTRING_INDEX(nombre, ' ', 2) as nombre
from nov_periodos
group by periodo
order by periodo desc";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los periodos
    }



}


?>