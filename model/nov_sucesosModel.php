<?php
//include_once("empleadosModel.php");

class Suceso
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

    private $id_periodo1;
    private $cantidad1;
    private $id_periodo2;
    private $cantidad2;
    private $fd1;
    private $fh1;
    private $fd2;
    private $fh2;


    //private $empleado;


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

    function getIdPeriodo1()
    { return $this->id_periodo1;}

    function getCantidad1()
    { return $this->cantidad1;}

    function getIdPeriodo2()
    { return $this->id_periodo2;}

    function getCantidad2()
    { return $this->cantidad2;}

    function getFd1()
    { return $this->fd1;}

    function getFh1()
    { return $this->fh1;}

    function getFd2()
    { return $this->fd2;}

    function getFh2()
    { return $this->fh2;}

    /*function getEmpleado(){
        return ($this->empleado)? $this->empleado : new Empleado() ;
    }*/


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

    function setIdPeriodo1($val)
    {  $this->id_periodo1=$val;}

    function setCantidad1($val)
    {  $this->cantidad1=$val;}

    function setIdPeriodo2($val)
    {  $this->id_periodo2=$val;}

    function setCantidad2($val)
    {  $this->cantidad2=$val;}

    function setFd1($val)
    {  $this->fd1=$val;}

    function setFh1($val)
    {  $this->fh1=$val;}

    function setFd2($val)
    {  $this->fd2=$val;}

    function setFh2($val)
    {  $this->fh2=$val;}



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
                    DATE_FORMAT(fh2,  '%d/%m/%Y') as fh2
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
            //$this->empleado = new Empleado($rows[0]['id_empleado']);
        }
    }


    public static function getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta, $id_contrato) { //ok
        $stmt=new sQuery();
        /*$query = "select su.id_suceso, su.id_evento, su.id_empleado,
                  DATE_FORMAT(su.fecha,  '%d/%m/%Y') as fecha,
                  DATE_FORMAT(su.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(su.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  su.observaciones,
                  CONCAT(em.apellido, ' ', em.nombre) as empleado,
                  ev.nombre as evento,
                  ev.codigo as txt_evento,
                  em.legajo as txt_legajo,
                  su.fecha_desde as txt_fecha_desde,
                  su.fecha_hasta as txt_fecha_hasta
                  from nov_sucesos su, empleados em, nov_eventos_l ev
                  where su.id_empleado = em.id_empleado
                  and su.id_evento = ev.id_evento
                  and su.id_empleado = ifnull(:id_empleado, su.id_empleado)
                  and su.id_evento in ($eventos)
                  and su.fecha_desde between if(:fecha_desde is null, su.fecha_desde, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                  and if(:fecha_hasta is null, su.fecha_hasta, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))"; */

        $query = "select su.id_suceso, su.id_evento, su.id_empleado,
                  DATE_FORMAT(su.created_date,  '%d/%m/%Y') as created_date,
                  DATE_FORMAT(su.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(su.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  su.observaciones,
                  CONCAT(em.legajo, ' ', em.apellido, ' ', em.nombre) as empleado,
                  ev.nombre as evento,
                  ev.codigo as txt_evento,
                  em.legajo as txt_legajo,
                  su.fecha_desde as txt_fecha_desde,
                  su.fecha_hasta as txt_fecha_hasta,
                  pe.closed_date
                  from v_sec_nov_sucesos su
                  join empleados em on su.id_empleado = em.id_empleado
                  join nov_eventos_l ev on su.id_evento = ev.id_evento
                  left join empleado_contrato ec on su.id_empleado = ec.id_empleado
                  join nov_periodos pe on pe.id_periodo = su.id_periodo1
                  where su.id_empleado = ifnull(:id_empleado, su.id_empleado)
                  and su.id_evento in ($eventos)
                  and su.fecha_desde <= if(:fecha_desde is null, su.fecha_desde, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                  and su.fecha_hasta >= if(:fecha_hasta is null, su.fecha_hasta, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))
                  and if(:id_contrato is not null, ec.id_contrato = :id_contrato, (ec.id_contrato = ec.id_contrato or ec.id_contrato is null))";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        //$stmt->dpBind(':id_evento', $id_evento);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
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
                      id_periodo1 = :id_periodo1,
                      cantidad1 = :cantidad1,
                      id_periodo2 = :id_periodo2,
                      cantidad2 = :cantidad2,
                      fd1 = STR_TO_DATE(:fd1, '%d/%m/%Y'),
                      fh1 = STR_TO_DATE(:fh1, '%d/%m/%Y'),
                      fd2 = STR_TO_DATE(:fd2, '%d/%m/%Y'),
                      fh2 = STR_TO_DATE(:fh2, '%d/%m/%Y')
                where id_suceso =:id_suceso";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_periodo1', $this->getIdPeriodo1());
        $stmt->dpBind(':cantidad1', $this->getCantidad1());
        $stmt->dpBind(':id_periodo2', $this->getIdPeriodo2());
        $stmt->dpBind(':cantidad2', $this->getCantidad2());
        $stmt->dpBind(':fd1', $this->getFd1());
        $stmt->dpBind(':fh1', $this->getFh1());
        $stmt->dpBind(':fd2', $this->getFd2());
        $stmt->dpBind(':fh2', $this->getFh2());
        $stmt->dpBind(':id_suceso', $this->getIdSuceso());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertSuceso(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_sucesos(id_evento, id_empleado, fecha_desde, fecha_hasta, observaciones, created_by, created_date, id_periodo1, cantidad1, id_periodo2, cantidad2, fd1, fh1, fd2, fh2)
                values(:id_evento, :id_empleado, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'), STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'), :observaciones, :created_by, sysdate(), :id_periodo1, :cantidad1, :id_periodo2, :cantidad2, STR_TO_DATE(:fd1, '%d/%m/%Y'), STR_TO_DATE(:fh1, '%d/%m/%Y'), STR_TO_DATE(:fd2, '%d/%m/%Y'), STR_TO_DATE(:fh2, '%d/%m/%Y'))";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpBind(':id_periodo1', $this->getIdPeriodo1());
        $stmt->dpBind(':cantidad1', $this->getCantidad1());
        $stmt->dpBind(':id_periodo2', $this->getIdPeriodo2());
        $stmt->dpBind(':cantidad2', $this->getCantidad2());
        $stmt->dpBind(':fd1', $this->getFd1());
        $stmt->dpBind(':fh1', $this->getFh1());
        $stmt->dpBind(':fd2', $this->getFd2());
        $stmt->dpBind(':fh2', $this->getFh2());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

        /*$stmt=new sQuery();
        $query = 'CALL sp_insertRenovacionPersonal(:id_vencimiento,
                                        :id_empleado,
                                        :id_grupo,
                                        :fecha_emision,
                                        :fecha_vencimiento,
                                        @flag
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : -1; */
    }

    function deleteSuceso(){ //ok
        $stmt=new sQuery();
        $query="delete from nov_sucesos where id_suceso =:id_suceso";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_suceso', $this->getIdSuceso());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsUpload($directory, $name, $id_suceso){ //ok
        $stmt=new sQuery();
        $query="insert into uploads_suceso(directory, name, fecha, id_suceso)
                values(:directory, :name, date(sysdate()), :id_suceso)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':name', $name);
        $stmt->dpBind(':id_suceso', $id_suceso);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsLoad($id_suceso) { //ok
        $stmt=new sQuery();
        $query = "select id_upload, directory, name, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, id_suceso
                from uploads_suceso
                where id_suceso = :id_suceso
                order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_suceso', $id_suceso);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function uploadsDelete($name){ //ok
        $stmt=new sQuery();
        $query="delete from uploads_suceso where name =:name";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':name', $name);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    /*public function checkFechaDesde($fecha_desde, $id_empleado, $id_evento, $id_suceso) { //obsoleto desde 17/05/2019
        //Busca que no exista un suceso para el id_empleado y id_evento, durante la fecha_desde ingresada
        $stmt=new sQuery();
        $query = "select *
                  from nov_sucesos
                  where id_empleado = :id_empleado
                  and id_evento = :id_evento
                  and STR_TO_DATE(:fecha_desde, '%d/%m/%Y') between fecha_desde and fecha_hasta
                  and id_suceso <> :id_suceso";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_suceso', $id_suceso);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_evento', $id_evento);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }*/

    /*public function checkFechaHasta($fecha_hasta, $id_empleado, $id_evento, $id_suceso) { //obsoleto desde 17/05/2019
        //Busca que no exista un suceso para el id_empleado y id_evento, durante la fecha_hasta ingresada
        $stmt=new sQuery();
        $query = "select *
                  from nov_sucesos
                  where id_empleado = :id_empleado
                  and id_evento = :id_evento
                  and STR_TO_DATE(:fecha_hasta, '%d/%m/%Y') between fecha_desde and fecha_hasta
                  and id_suceso <> :id_suceso";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_suceso', $id_suceso);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_evento', $id_evento);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }*/

    public function checkRango($fecha_desde, $fecha_hasta, $id_empleado, $id_evento, $id_suceso) { //ok
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


    public static function exportTxt($id_contrato, $id_periodo) {
        $stmt=new sQuery();
        /*$query = "select ns.id_suceso, ns.id_evento, ns.id_empleado,
                  DATE_FORMAT(ns.created_date,  '%d/%m/%Y') as created_date,
                  DATE_FORMAT(ns.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(ns.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  ns.fecha_desde as txt_fecha_desde,
                  ns.fecha_hasta as txt_fecha_hasta,
                  (if(ns.id_periodo1 = :id_periodo, ifnull(ns.cantidad1,0), 0) + if(ns.id_periodo2 = :id_periodo, ifnull(ns.cantidad2,0), 0)) as cantidad,
                  ns.observaciones,
                  ev.nombre as evento,
                  ev.codigo as txt_evento,
                  em.legajo as txt_legajo
                  from v_sec_nov_sucesos ns
                  join empleados em on ns.id_empleado = em.id_empleado
                  join nov_eventos_l ev on ns.id_evento = ev.id_evento
                  left join empleado_contrato ec on ns.id_empleado = ec.id_empleado
                  where ec.id_contrato = :id_contrato
                  and (ns.id_periodo1 = :id_periodo or ns.id_periodo2 = :id_periodo)";*/
        $query = "select ns.id_suceso, ns.id_evento, ns.id_empleado,
                  if(ns.id_periodo1 = :id_periodo, ns.fd1, ns.fd2) as fecha_desde,
                  if(ns.id_periodo1 = :id_periodo, ns.fh1, ns.fh2) as fecha_hasta,
                  per.fecha_desde as periodo_desde,
                  per.fecha_hasta as periodo_hasta,
                  if(ns.id_periodo1 = :id_periodo, ifnull(ns.cantidad1,0), ifnull(ns.cantidad2,0)) as cantidad,
                  ev.codigo as evento,
                  em.legajo as legajo
                  from v_sec_nov_sucesos ns
                  join empleados em on ns.id_empleado = em.id_empleado
                  join nov_eventos_l ev on ns.id_evento = ev.id_evento
                  left join empleado_contrato ec on ns.id_empleado = ec.id_empleado
                  join nov_periodos per on per.id_periodo = :id_periodo
                  where ec.id_contrato = :id_contrato
                  and (ns.id_periodo1 = :id_periodo or ns.id_periodo2 = :id_periodo)";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_periodo', $id_periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();


    }






}




?>