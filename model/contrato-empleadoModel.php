<?php
include_once("model/contrato-empleado-procesoModel.php");

class ContratoEmpleado
{
    private $id_empleado_contrato;
    private $id_empleado;
    private $id_contrato;
    private $id_puesto;
    private $fecha_desde;
    private $fecha_hasta;
    private $id_localidad;

    private $procesos;

    //GETTERS
    function getIdEmpleadoContrato()
    { return $this->id_empleado_contrato;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getIdPuesto()
    { return $this->id_puesto;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getIdLocalidad()
    { return $this->id_localidad;}

    function getProcesos(){
        return $this->procesos;
    }

    //SETTERS
    function setIdEmpleadoContrato($val)
    { $this->id_empleado_contrato=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}

    function setIdPuesto($val)
    {  $this->id_puesto=$val;}

    function setFechaDesde($val)
    {  $this->fecha_desde=$val;}

    function setFechaHasta($val)
    {  $this->fecha_hasta=$val;}

    function setIdLocalidad($val)
    {  $this->id_localidad=$val;}



    function __construct($nro=0){ //constructor ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_empleado_contrato, id_empleado, id_contrato, id_puesto,
                    DATE_FORMAT(fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(fecha_hasta,  '%d/%m/%Y') as fecha_hasta, id_localidad
                    from empleado_contrato where id_empleado_contrato = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEmpleadoContrato($rows[0]['id_empleado_contrato']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setIdLocalidad($rows[0]['id_localidad']);

            $this->procesos = array();
            $this->procesos = ContratoEmpleadoProceso::getContratoEmpleadoProceso($this->getIdEmpleadoContrato());
        }
    }

    //Devuelve todos los empleados de un determinado contrato
    public static function getContratoEmpleado($id_contrato) { //ok
        /*$stmt=new sQuery();
        $query = "select ec.id_empleado_contrato, ec.id_empleado, ec.id_contrato, ec.id_puesto,
                  DATE_FORMAT(ec.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(ec.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  CONCAT (em.apellido, ' ', em.nombre) as empleado,
                  pu.nombre as puesto
                  from empleado_contrato ec, empleados em, puestos pu
                  where ec.id_empleado = em.id_empleado
                  and ec.id_puesto = pu.id_puesto
                  and ec.id_contrato = :id_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();*/

        $detalle = array();

        $stmt=new sQuery();
        $query = "select ec.id_empleado_contrato, ec.id_empleado, ec.id_contrato, ec.id_puesto,
                  DATE_FORMAT(ec.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(ec.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  ec.id_localidad,
                  CONCAT (em.apellido, ' ', em.nombre) as empleado,
                  pu.nombre as puesto
                  from empleado_contrato ec, empleados em, puestos pu
                  where ec.id_empleado = em.id_empleado
                  and ec.id_puesto = pu.id_puesto
                  and ec.id_contrato = :id_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        $rows = $stmt ->dpFetchAll();

        //Se debe formatear de esta manera para poder enviar un array de objetos a javascript
        foreach($rows as $row){
            $unEmpleado = new ContratoEmpleado($row['id_empleado_contrato']);
            $detalle[] = array( 'id_empleado_contrato'=>$row['id_empleado_contrato'],
                                'id_empleado'=>$row['id_empleado'],
                                'id_contrato'=>$row['id_contrato'],
                                'id_puesto'=>$row['id_puesto'],
                                'fecha_desde'=>$row['fecha_desde'],
                                'fecha_hasta'=>$row['fecha_hasta'],
                                'id_localidad'=>$row['id_localidad'],
                                'empleado'=>$row['empleado'],
                                'puesto'=>$row['puesto'],
                                'id_proceso'=>$unEmpleado->getProcesos()
                                );
        }

        return $detalle;

    }



    //Devuelve todos los contratos de un determinado empleado
    public static function getContratosByEmpleado($id_empleado) { //ok
        $stmt=new sQuery();
        /*$query = "select ec.id_empleado_contrato, ec.id_empleado, ec.id_contrato, ec.id_puesto,
                  DATE_FORMAT(ec.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(ec.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  co.nro_contrato,
                  co.nombre as contrato,
                  pu.nombre as puesto,
                  datediff(ec.fecha_hasta, sysdate()) as days_left
                  from empleado_contrato ec, contratos co, puestos pu
                  where ec.id_contrato = co.id_contrato
                  and ec.id_puesto = pu.id_puesto
                  and ec.id_empleado = :id_empleado
                  order by ec.fecha_desde desc";*/

        $query = "select ec.id_empleado_contrato, ec.id_empleado, ec.id_contrato, ec.id_puesto,
DATE_FORMAT(ec.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(ec.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
co.nro_contrato,
co.nombre as contrato,
pu.nombre as puesto,
concat(loc.ciudad, ' ', loc.provincia) as localidad,
datediff(ec.fecha_hasta, sysdate()) as days_left
from empleado_contrato ec
join contratos co on ec.id_contrato = co.id_contrato
join puestos pu on ec.id_puesto = pu.id_puesto
left join localidades loc on ec.id_localidad = loc.id_localidad
where ec.id_empleado = :id_empleado
order by ec.fecha_desde desc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public function updateEmpleadoContrato(){ //ok

        $stmt=new sQuery();
        $query="update empleado_contrato
                set id_puesto= :id_puesto,
                fecha_desde= STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                fecha_hasta= STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                id_localidad = :id_localidad
                where id_empleado_contrato = :id_empleado_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpBind(':id_empleado_contrato', $this->getIdEmpleadoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    public function insertEmpleadoContrato(){ //ok

        $stmt=new sQuery();
        $query="insert into empleado_contrato(id_empleado, id_contrato, id_puesto, fecha_desde, fecha_hasta, id_localidad)
                values(:id_empleado, :id_contrato, :id_puesto,
                STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                :id_localidad)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public function deleteEmpleadoContrato(){ //ok
        $stmt=new sQuery();
        $query="delete from empleado_contrato where id_empleado_contrato= :id_empleado_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado_contrato', $this->getIdEmpleadoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

}

?>