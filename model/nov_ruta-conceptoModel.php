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
                    DATE_FORMAT(fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                    id_localidad
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
    public static function getVehiculos($id_contrato) { //ok
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


    function save(){ //ok
        if($this->id_vehiculo_contrato)
        {$rta = $this->updateVehiculoContrato();}
        else
        {$rta =$this->insertVehiculoContrato();}
        return $rta;
    }



    public function updateVehiculoContrato(){ //ok

        $stmt=new sQuery();
        $query="update vto_vehiculo_contrato
                set id_vehiculo = :id_vehiculo,
                fecha_desde = STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                fecha_hasta = STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                id_localidad = :id_localidad
                where id_vehiculo_contrato = :id_vehiculo_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpBind(':id_vehiculo_contrato', $this->getIdVehiculoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    public function insertVehiculoContrato(){ //ok

        $stmt=new sQuery();
        $query="insert into vto_vehiculo_contrato(id_vehiculo, id_contrato, fecha_desde, fecha_hasta, id_localidad)
                values(:id_vehiculo, :id_contrato,
                STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                :id_localidad)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public function deleteVehiculoContrato(){ //ok
        $stmt=new sQuery();
        $query="delete from vto_vehiculo_contrato where id_vehiculo_contrato= :id_vehiculo_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo_contrato', $this->getIdVehiculoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkVehiculo($id_vehiculo, $id_contrato, $id_contrato_vehiculo) { //ok
        //verifica que el vehiculo no se repita dentro de un contrato
        $stmt=new sQuery();
        $query = "select 1
from vto_vehiculo_contrato vvc
where vvc.id_vehiculo = :id_vehiculo
and vvc.id_contrato = :id_contrato
and vvc.id_vehiculo_contrato <> :id_contrato_vehiculo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_contrato_vehiculo', $id_contrato_vehiculo);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }


}

?>