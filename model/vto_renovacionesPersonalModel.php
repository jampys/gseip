<?php
include_once("empleadosModel.php");

class RenovacionPersonal
{
    private $id_renovacion;
    private $id_vencimiento;
    private $id_empleado;
    private $fecha_emision;
    private $fecha_vencimiento;
    private $alert_status;
    private $fecha;
    private $id_rnv_renovacion; //id_renovacion que le sigue

    private $empleado;


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

    function getEmpleado(){
        return ($this->empleado)? $this->empleado : new Empleado() ;
    }

    function getIdRnvRenovacion()
    { return $this->id_rnv_renovacion;}


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

    function setIdRnvRenovacion($val)
    { $this->id_rnv_renovacion=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            /*$query="select id_renovacion, id_vencimiento, id_empleado,
                    DATE_FORMAT(fecha_emision,  '%d/%m/%Y') as fecha_emision,
                    DATE_FORMAT(fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
                    alert_status,
                    DATE_FORMAT(fecha,  '%d/%m/%Y') as fecha
                    from vto_renovacion_p
                    where id_renovacion = :nro";*/
            $query = "select id_renovacion, id_vencimiento, id_empleado,
                    DATE_FORMAT(fecha_emision,  '%d/%m/%Y') as fecha_emision,
                    DATE_FORMAT(fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
                    DATE_FORMAT(fecha,  '%d/%m/%Y') as fecha, id_rnv_renovacion
                    from v_vto_renovacion_p
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
            $this->setIdRnvRenovacion($rows[0]['id_rnv_renovacion']);

            $this->empleado = new Empleado($rows[0]['id_empleado']);
        }
    }


    public static function getRenovacionesPersonal() { //ok
        $stmt=new sQuery();
        /*$query = "select v_renov_p.id_renovacion,
v_vto_p.nombre as vencimiento,
CONCAT(em.apellido, ' ', em.nombre) as empleado,
DATE_FORMAT(v_renov_p.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(v_renov_p.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(v_renov_p.fecha,  '%d/%m/%Y') as fecha
from vto_renovacion_p v_renov_p
join vto_vencimiento_p v_vto_p on v_renov_p.id_vencimiento = v_vto_p.id_vencimiento
join empleados em on v_renov_p.id_empleado = em.id_empleado";*/
        $query = "select vrp.id_renovacion, vrp.id_vencimiento, vrp.id_empleado,
DATE_FORMAT(vrp.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(vrp.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(vrp.fecha,  '%d/%m/%Y') as fecha,
vvp.nombre as vencimiento,
vav.id_alerta, vav.days,
va.color, va.priority,
CONCAT(em.apellido, ' ', em.nombre) as empleado,
vrp.id_rnv_renovacion
from v_vto_renovacion_p vrp, vto_vencimiento_p vvp, vto_alerta_vencimiento_p vav, empleados em, vto_alerta va
where vrp.id_vencimiento = vvp.id_vencimiento
and vav.id_vencimiento = vrp.id_vencimiento
and vrp.id_empleado = em.id_empleado
and vav.id_alerta = va.id_alerta
and vav.id_alerta = func_alerta(vrp.id_renovacion)
order by va.priority, vrp.id_rnv_renovacion asc";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_renovacion)
        {$rta = $this->updateRenovacion();}
        else
        {$rta =$this->insertRenovacion();}
        return $rta;
    }


    public function updateRenovacion(){ //ok
        $stmt=new sQuery();
        $query="update vto_renovacion_p set id_vencimiento =:id_vencimiento,
                      fecha_emision = STR_TO_DATE(:fecha_emision, '%d/%m/%Y'),
                      fecha_vencimiento = STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y')
                where id_renovacion =:id_renovacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpBind(':id_renovacion', $this->getIdRenovacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertRenovacion(){
        $stmt=new sQuery();
        $query="insert into vto_renovacion_p(id_vencimiento, id_empleado, fecha_emision, fecha_vencimiento, fecha)
                values(:id_vencimiento, :id_empleado, STR_TO_DATE(:fecha_emision, '%d/%m/%Y'), STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y'), date(sysdate()))";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteHabilidad(){
        $stmt=new sQuery();
        $query="delete from habilidades where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsUpload($directory, $name, $id_renovacion){ //ok
        $stmt=new sQuery();
        $query="insert into uploads_vencimiento_p(directory, name, fecha, id_renovacion)
                values(:directory, :name, date(sysdate()), :id_renovacion)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':name', $name);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsLoad($id_renovacion) { //ok
        $stmt=new sQuery();
        $query = "select *
                from uploads_vencimiento_p
                where id_renovacion = :id_renovacion
                order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function uploadsDelete($name){ //ok
        $stmt=new sQuery();
        $query="delete from uploads_vencimiento_p where name =:name";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':name', $name);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}




?>