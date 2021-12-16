<?php

class CapacitacionEmpleado
{
    private $id_capacitacion_empleado;
    private $id_empleado;
    private $id_capacitacion;
    private $id_contrato;
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
            $query = "select id_capacitacion_empleado, id_empleado, id_capacitacion, id_contrato, asistio, observaciones,
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
            $this->setAsistio($rows[0]['asistio']);
            $this->setObservaciones($rows[0]['observaciones']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getEmpleados($id_capacitacion) { //ok
        $stmt=new sQuery();
        $query = "select ce.id_capacitacion_empleado, ce.id_empleado, ce.id_capacitacion, ce.id_contrato, ce.asistio, ce.observaciones,
                  ce.id_user,
                  DATE_FORMAT(ce.created_date, '%d/%m/%Y') as created_date,
                  us.user,
                  concat(em.legajo, ' ', em.apellido, ' ', em.nombre) as empleado,
                  co.nombre as contrato
                  from cap_capacitacion_empleado ce
                  join cap_capacitaciones c on c.id_capacitacion = ce.id_capacitacion
                  join contratos co on co.id_contrato = ce.id_contrato
                  join sec_users us on ce.id_user = us.id_user
                  join empleados em on em.id_empleado = ce.id_empleado
                  where ce.id_capacitacion = :id_capacitacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_capacitacion', $id_capacitacion);
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


    public function updateCapacitacionEmpleado(){
        $stmt=new sQuery();
        $query="update nc_accion set accion= :accion,
                id_responsable_ejecucion = :id_responsable_ejecucion,
                fecha_implementacion = STR_TO_DATE(:fecha_implementacion, '%d/%m/%Y')
                where id_accion = :id_accion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':accion', $this->getAccion());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':fecha_implementacion', $this->getFechaImplementacion());
        $stmt->dpBind(':id_accion', $this->getIdAccion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertCapacitacionEmpleado(){
        $stmt=new sQuery();
        $query="insert into nc_accion(id_no_conformidad, accion, id_responsable_ejecucion, fecha_implementacion, id_user, created_date)
                values(:id_no_conformidad, :accion, :id_responsable_ejecucion, STR_TO_DATE(:fecha_implementacion, '%d/%m/%Y'), :id_user, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_no_conformidad', $this->getIdNoConformidad());
        $stmt->dpBind(':accion', $this->getAccion());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':fecha_implementacion', $this->getFechaImplementacion());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteCapacitacionEmpleado(){
        $stmt=new sQuery();
        $query="delete from nc_accion where id_accion = :id_accion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_accion', $this->getIdAccion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}




?>