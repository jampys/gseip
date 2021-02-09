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
                    id_periodo1, cantidad1, id_periodo2, cantidad2,
                    DATE_FORMAT(fd1,  '%d/%m/%Y') as fd1,
                    DATE_FORMAT(fh1,  '%d/%m/%Y') as fh1,
                    DATE_FORMAT(fd2,  '%d/%m/%Y') as fd2,
                    DATE_FORMAT(fh2,  '%d/%m/%Y') as fh2,
                    id_parte, programado, periodo
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
            $this->setIdPeriodo1($rows[0]['id_periodo1']);
            $this->setCantidad1($rows[0]['cantidad1']);
            $this->setIdPeriodo2($rows[0]['id_periodo2']);
            $this->setCantidad2($rows[0]['cantidad2']);
            $this->setFd1($rows[0]['fd1']);
            $this->setFh1($rows[0]['fh1']);
            $this->setFd2($rows[0]['fd2']);
            $this->setFh2($rows[0]['fh2']);
            $this->setIdParte($rows[0]['id_parte']);
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
        $query="insert into nov_sucesos(id_evento, id_empleado, fecha_desde, fecha_hasta, observaciones, created_by, created_date, id_contrato, programado)
                values(:id_evento, :id_empleado, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'), STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'), :observaciones, :created_by, sysdate(), :id_contrato, :programado)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':programado', $this->getProgramado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }



    public function checkRango($fecha_desde, $fecha_hasta, $id_empleado, $id_evento, $id_suceso) {
        //Busca que no exista un suceso para el id_empleado y id_evento, durante la fecha_hasta ingresada
        $stmt=new sQuery();
        $query = "select *
                  from nov_sucesos
                  where id_empleado = :id_empleado
                  and id_evento = :id_evento
                  and STR_TO_DATE(:fecha_desde, '%d/%m/%Y') <= fecha_hasta
                  and STR_TO_DATE(:fecha_hasta, '%d/%m/%Y') >= fecha_desde
                  and id_suceso <> :id_suceso";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_suceso', $id_suceso);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_evento', $id_evento);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }




}




?>