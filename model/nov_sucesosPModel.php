<?php

class SucesoP
{
    private $id_suceso;
    private $id_evento;
    private $id_empleado;
    private $fecha;
    private $fecha_desde;
    private $fecha_hasta;
    private $observaciones;
    private $created_by;
    private $created_date;
    private $cantidad1;
    private $id_contrato;
    private $programado;


    // GETTERS
    function getIdSuceso()
    { return $this->id_suceso;}

    function getIdEvento()
    { return $this->id_evento;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getFecha()
    { return $this->fecha;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getObservaciones()
    { return $this->observaciones;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}

    function getCantidad1()
    { return $this->cantidad1;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getProgramado()
    { return $this->programado;}


    //SETTERS
    function setIdSuceso($val)
    { $this->id_suceso=$val;}

    function setIdEvento($val)
    {  $this->id_evento=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setFechaDesde($val)
    { $this->fecha_desde=$val;}

    function setFechaHasta($val)
    { $this->fecha_hasta=$val;}

    function setObservaciones($val)
    { $this->observaciones=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}

    function setCantidad1($val)
    {  $this->cantidad1=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}

    function setProgramado($val)
    {  $this->programado=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_suceso, id_evento, id_empleado,
                    DATE_FORMAT(fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                    observaciones,
                    created_by,
                    DATE_FORMAT(created_date,  '%d/%m/%Y') as created_date,
                    cantidad1
                    programado, id_contrato
                    from nov_sucesos
                    where id_suceso = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdSuceso($rows[0]['id_suceso']);
            $this->setIdEvento($rows[0]['id_evento']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setFecha($rows[0]['fecha']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setObservaciones($rows[0]['observaciones']);
            $this->setCreatedDate($rows[0]['created_date']);
            $this->setCantidad1($rows[0]['cantidad1']);
            $this->setProgramado($rows[0]['programado']);
            $this->setIdContrato($rows[0]['id_contrato']);

        }
    }



    function save(){ //ok
        if($this->id_suceso)
        {$rta = $this->updateSuceso();}
        else
        {$rta =$this->insertSuceso();}
        return $rta;
    }


    public function updateSuceso(){ //ok
        $stmt=new sQuery();
        $query="update nov_sucesos set id_evento =:id_evento,
                      fecha_desde = STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                      fecha_hasta = STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                      observaciones = :observaciones,
                      id_contrato = :id_contrato,
                      programado = :programado,
                where id_suceso =:id_suceso";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':programado', $this->getProgramado());
        $stmt->dpBind(':id_suceso', $this->getIdSuceso());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertSuceso(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_sucesos(id_evento, id_empleado, fecha_desde, fecha_hasta, observaciones, created_by, created_date, cantidad1, id_contrato, programado)
                values(:id_evento, :id_empleado, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'), STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'), :observaciones, :created_by, sysdate(), :cantidad1, :id_contrato, :programado)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpBind(':cantidad1', $this->getCantidad1());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':programado', $this->getProgramado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }




}




?>