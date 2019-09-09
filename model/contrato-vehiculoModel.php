<?php

class ContratoVehiculo
{
    private $id_vehiculo_contrato;
    private $id_vehiculo;
    private $id_contrato;
    private $fecha_desde;
    private $fecha_hasta;
    private $id_localidad;

    //GETTERS
    function getIdVehiculoContrato()
    { return $this->id_vehiculo_contrato;}

    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getIdLocalidad()
    { return $this->id_localidad;}


    //SETTERS
    function setIdVehiculoContrato($val)
    { $this->id_vehiculo_contrato=$val;}

    function setIdVehiculo($val)
    { $this->id_vehiculo=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}

    function setFechaDesde($val)
    {  $this->fecha_desde=$val;}

    function setFechaHasta($val)
    {  $this->fecha_hasta=$val;}

    function setIdLocalidad($val)
    {  $this->id_localidad=$val;}



    function __construct($nro=0){ //constructor //ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_vehiculo_contrato, id_vehiculo, id_contrato,
                    DATE_FORMAT(fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(fecha_hasta,  '%d/%m/%Y') as fecha_hasta, id_localidad
                    from vto_vehiculo_contrato where id_vehiculo_contrato = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdVehiculoContrato($rows[0]['id_vehiculo_contrato']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setIdLocalidad($rows[0]['id_localidad']);

        }
    }

    //Devuelve todos los vehiculos de un determinado contrato
    public static function getContratoVehiculo($id_contrato) { //ok
        $stmt=new sQuery();
        $query = "select vc.id_vehiculo_contrato, vc.id_vehiculo, vc.id_contrato,
DATE_FORMAT(vc.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(vc.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
v.matricula, v.nro_movil, v.marca, v.modelo
from vto_vehiculo_contrato vc
join vto_vehiculos v on v.id_vehiculo = vc.id_vehiculo
where vc.id_contrato = :id_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();

    }



    //Devuelve todos los contratos de un determinado vehiculo
    public static function getContratosByVehiculo($id_vehiculo) {
        $stmt=new sQuery();
        $query = "select vc.id_vehiculo_contrato, vc.id_vehiculo, vc.id_contrato,
DATE_FORMAT(vc.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(vc.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
co.nro_contrato,
co.nombre as contrato,
concat(loc.ciudad, ' ', loc.provincia) as localidad,
datediff(vc.fecha_hasta, sysdate()) as days_left
from vto_vehiculo_contrato vc
join contratos co on vc.id_contrato = co.id_contrato
left join localidades loc on vc.id_localidad = loc.id_localidad
where vc.id_vehiculo = :id_vehiculo
order by vc.fecha_desde desc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public function updateEmpleadoContrato(){

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

    public function insertEmpleadoContrato(){

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

    public function deleteEmpleadoContrato(){
        $stmt=new sQuery();
        $query="delete from empleado_contrato where id_empleado_contrato= :id_empleado_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado_contrato', $this->getIdEmpleadoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

}

?>