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



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_suceso, id_evento, id_empleado,
                    DATE_FORMAT(fecha,  '%d/%m/%Y') as fecha,
                    DATE_FORMAT(fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                    observaciones
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
                  from nov_sucesos su
                  join empleados em on su.id_empleado = em.id_empleado
                  join nov_eventos_l ev on su.id_evento = ev.id_evento
                  left join empleado_contrato ec on su.id_empleado = ec.id_empleado
                  where su.id_empleado = ifnull(:id_empleado, su.id_empleado)
                  and su.id_evento in ($eventos)
                  and su.fecha_desde between if(:fecha_desde is null, su.fecha_desde, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                  and if(:fecha_hasta is null, su.fecha_hasta, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))
                  and if(:id_contrato is not null, ec.id_contrato = :id_contrato, (ec.id_contrato = ec.id_contrato or ec.id_contrato is null))
                  group by su.id_suceso";

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
                      observaciones = :observaciones
                where id_suceso =:id_suceso";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_suceso', $this->getIdSuceso());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertSuceso(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_sucesos(id_evento, id_empleado, fecha, fecha_desde, fecha_hasta, observaciones)
                values(:id_evento, :id_empleado, sysdate(), STR_TO_DATE(:fecha_desde, '%d/%m/%Y'), STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'), :observaciones)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
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

    function deleteHabilidad(){
        $stmt=new sQuery();
        $query="delete from habilidades where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
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


    public function checkFechaDesde($fecha_desde, $id_empleado, $id_evento, $id_suceso) { //ok
        /*Busca que no exista un suceso para el id_empleado y id_evento, durante la fecha_desde ingresada */
        $stmt=new sQuery();
        /*$query = "select *
                  from nov_sucesos
                  where id_empleado = :id_empleado
                  and id_evento = :id_evento
                  and
                  (( -- renovar: busca renovacion vigente y se asegura que la fecha_emision ingresada sea mayor que la de ésta
                  :id_suceso is null
                  and STR_TO_DATE(:fecha_desde, '%d/%m/%Y') between fecha_desde and fecha_hasta
                  )
                  OR
                  ( -- editar: busca renovacion anterior y ....
                  :id_suceso is not null
                  and STR_TO_DATE(:fecha_desde, '%d/%m/%Y') between fecha_desde and fecha_hasta
                  and id_suceso <> :id_suceso
                  ))
                  order by fecha_desde asc
                  limit 1";*/

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
    }

    public function checkFechaHasta($fecha_hasta, $id_empleado, $id_evento, $id_suceso) { //ok
        /*Busca que no exista un suceso para el id_empleado y id_evento, durante la fecha_hasta ingresada */
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
    }






}




?>