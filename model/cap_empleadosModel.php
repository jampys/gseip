<?php

class CapacitacionEmpleado
{
    private $id_capacitacion_empleado;
    private $id_empleado;
    private $id_capacitacion;
    private $id_contrato;
    private $id_edicion;
    private $asistio;
    private $observaciones;
    private $id_user;
    private $created_date;

    // GETTERS
    function getIdCapacitacionEmpleado()
    { return $this->id_capacitacion_empleado;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getIdCapacitacion()
    { return $this->id_capacitacion;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getIdEdicion()
    { return $this->id_edicion;}

    function getAsistio()
    { return $this->asistio;}

    function getObservaciones()
    { return $this->observaciones;}

    function getIdUser()
    { return $this->id_user;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdCapacitacionEmpleado($val)
    { $this->id_capacitacion_empleado=$val;}

    function setIdEmpleado($val)
    {  $this->id_empleado=$val;}

    function setIdCapacitacion($val)
    {  $this->id_capacitacion=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}

    function setIdEdicion($val)
    {  $this->id_edicion=$val;}

    function setAsistio($val)
    { $this->asistio=$val;}

    function setObservaciones($val)
    { $this->observaciones=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_capacitacion_empleado, id_empleado, id_capacitacion, id_contrato, id_edicion, asistio, observaciones,
                      id_user,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from cap_capacitacion_empleado
                      where id_capacitacion_empleado = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdCapacitacionEmpleado($rows[0]['id_capacitacion_empleado']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdCapacitacion($rows[0]['id_capacitacion']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setIdEdicion($rows[0]['id_edicion']);
            $this->setAsistio($rows[0]['asistio']);
            $this->setObservaciones($rows[0]['observaciones']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getEmpleados($id_capacitacion, $id_edicion = null, $id_contrato, $startDate, $endDate) { //ok
        $stmt=new sQuery();
        $query = "select ce.id_capacitacion_empleado, ce.id_empleado, ce.id_capacitacion, ce.id_contrato, ce.asistio, ce.observaciones,
                  ce.id_user,
                  DATE_FORMAT(ce.created_date, '%d/%m/%Y') as created_date,
                  us.user,
                  concat(em.apellido, ' ', em.nombre) as empleado,
                  co.nombre as contrato,
                  concat(em.apellido, ' ', em.nombre) as empleado,
                  DATE_FORMAT(ed.fecha_edicion, '%d/%m/%Y') as fecha_edicion,
                  concat(DATE_FORMAT(ed.fecha_edicion, '%d/%m/%Y'), ' ', ed.nombre) as edicion
                  from cap_capacitacion_empleado ce
                  join cap_capacitaciones c on c.id_capacitacion = ce.id_capacitacion
                  left join contratos co on co.id_contrato = ce.id_contrato
                  join sec_users us on ce.id_user = us.id_user
                  join empleados em on em.id_empleado = ce.id_empleado
                  left join cap_ediciones ed on ed.id_edicion = ce.id_edicion
                  where ce.id_capacitacion = :id_capacitacion
                  -- and ce.id_edicion = ifnull(:id_edicion, ce.id_edicion)
                  and if(:id_edicion, ce.id_edicion = :id_edicion, 1)
                  and if(ce.id_contrato is not null, ce.id_contrato in ($id_contrato), 1)
                  -- and date(ed.fecha_edicion) between :startDate and :endDate
                  and if(:id_edicion, date(ed.fecha_edicion) between :startDate and :endDate, 1)
                  order by ed.fecha_edicion, co.nombre, em.apellido";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_capacitacion', $id_capacitacion);
        $stmt->dpBind(':id_edicion', $id_edicion);
        $stmt->dpBind(':startDate', $startDate);
        $stmt->dpBind(':endDate', $endDate);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_capacitacion_empleado)
        {$rta = $this->updateCapacitacionEmpleado();}
        else
        {$rta =$this->insertCapacitacionEmpleado();}
        return $rta;
    }


    public function updateCapacitacionEmpleado(){ //ok
        $stmt=new sQuery();
        $query="update cap_capacitacion_empleado set id_contrato= :id_contrato, id_edicion= :id_edicion,
                asistio = :asistio,
                observaciones = :observaciones
                where id_capacitacion_empleado = :id_capacitacion_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_edicion', $this->getIdEdicion());
        $stmt->dpBind(':asistio', $this->getAsistio());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_capacitacion_empleado', $this->getIdCapacitacionEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertCapacitacionEmpleado(){ //ok
        $stmt=new sQuery();
        $query="insert into cap_capacitacion_empleado(id_empleado, id_capacitacion, id_contrato, id_edicion, asistio, observaciones, id_user, created_date)
                values(:id_empleado, :id_capacitacion, :id_contrato, :id_edicion, :asistio, :observaciones, :id_user, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_capacitacion', $this->getIdCapacitacion());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_edicion', $this->getIdEdicion());
        $stmt->dpBind(':asistio', $this->getAsistio());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteCapacitacionEmpleado(){ //ok
        $stmt=new sQuery();
        $query="delete from cap_capacitacion_empleado where id_capacitacion_empleado = :id_capacitacion_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_capacitacion_empleado', $this->getIdCapacitacionEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}




?>