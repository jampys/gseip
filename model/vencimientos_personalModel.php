<?php

class Vencimiento_personal
{
    private $id_renovacion;
    private $id_vencimiento;
    private $id_empleado;
    private $fecha_emision;
    private $fecha_vencimiento;
    private $alert_status;
    private $fecha;


    // GETTERS
    function getIdRenovacion()
    { return $this->id_renovacion;}

    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getFechaEmision()
    { return $this->fecha_emision;}

    function getFechaVencimiento()
    { return $this->fecha_vencimiento;}

    function getAlertStatus()
    { return $this->alert_status;}

    function getFecha()
    { return $this->fecha;}


    //SETTERS
    function setIdRenovacion($val)
    { $this->id_renovacion=$val;}

    function setIdVencimiento($val)
    {  $this->id_vencimiento=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setFechaEmision($val)
    { $this->fecha_emision=$val;}

    function setFechaVencimiento($val)
    { $this->fecha_vencimiento=$val;}

    function setAlertStatus($val)
    { $this->alert_status=$val;}

    function setFecha($val)
    { $this->fecha=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select *
                    --DATE_FORMAT(em.fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                    --DATE_FORMAT(em.fecha_alta,  '%d/%m/%Y') as fecha_alta,
                    from vto_renovacion_p
                    where id_renovacion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdRenovacion($rows[0]['id_renovacion']);
            $this->setIdVencimiento($rows[0]['id_vencimiento']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setFechaEmision($rows[0]['fecha_emision']);
            $this->setFechaVencimiento($rows[0]['fecha_vencimiento']);
            $this->setAlertStatus($rows[0]['alert_status']);
            $this->setFecha($rows[0]['fecha']);
        }
    }


    public static function getVencimientosPersonal() { //ok
        $stmt=new sQuery();
        $query = "select v_renov_p.id_renovacion,
v_vto_p.nombre as vencimiento,
CONCAT(em.apellido, ' ', em.nombre) as empleado,
DATE_FORMAT(v_renov_p.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(v_renov_p.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(v_renov_p.fecha,  '%d/%m/%Y') as fecha
from vto_renovacion_p v_renov_p
join vto_vencimiento_p v_vto_p on v_renov_p.id_vencimiento = v_vto_p.id_vencimiento
join empleados em on v_renov_p.id_empleado = em.id_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){
        if($this->id_habilidad)
        {$rta = $this->updateHabilidad();}
        else
        {$rta =$this->insertHabilidad();}
        return $rta;
    }


    public function updateHabilidad(){
        $stmt=new sQuery();
        $query="update habilidades set codigo =:codigo, nombre =:nombre
                where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
        //echo "conexion comun ".print_r(squery::dpGetConnectionId() )."ooooo";

    }

    private function insertHabilidad(){
        $stmt=new sQuery();
        $query="insert into habilidades(codigo, nombre)
                values(:codigo, :nombre)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
        //echo "last ide ".print_r(squery::dpLastInsertId() )."ooooo";
    }

    function deleteHabilidad(){
        $stmt=new sQuery();
        $query="delete from habilidades where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function autocompletarHabilidades($term) {
        $stmt=new sQuery();
        $query = "select *
                  from habilidades
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }




    public static function getHabilidadesImg($id) { //ok
        $stmt=new sQuery();
        $query = "select *
                from uploads
                where id = :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $id);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }




}




?>