<?php

class RutaConcepto
{
    private $id_ruta_concepto;
    private $id_ruta;
    private $id_concepto_convenio_contrato;
    private $cantidad;

    //GETTERS
    function getIdRutaConcepto()
    { return $this->id_ruta_concepto;}

    function getIdRuta()
    { return $this->id_ruta;}

    function getIdConceptoConvenioContrato()
    { return $this->id_concepto_convenio_contrato;}

    function getCantidad()
    { return $this->cantidad;}


    //SETTERS
    function setIdRutaConcepto($val)
    { $this->id_ruta_concepto=$val;}

    function setIdRuta($val)
    { $this->id_ruta=$val;}

    function setIdConceptoConvenioContrato($val)
    {  $this->id_concepto_convenio_contrato=$val;}

    function setCantidad($val)
    {  $this->cantidad=$val;}



    function __construct($nro=0){ //constructor //ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select nrc.id_ruta_concepto, nrc.id_ruta, nrc.id_concepto_convenio_contrato, nrc.cantidad
                    from nov_ruta_concepto nrc
                    where id_ruta_concepto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdRutaConcepto($rows[0]['id_ruta_concepto']);
            $this->setIdRuta($rows[0]['id_ruta']);
            $this->setIdConceptoConvenioContrato($rows[0]['id_concepto_convenio_contrato']);
            $this->setCantidad($rows[0]['cantidad']);

        }
    }

    //Devuelve todos los conceptos de una determinada ruta
    public static function getConceptos($id_ruta) { //ok
        $stmt=new sQuery();
        $query = "select nrc.id_ruta_concepto, nrc.id_ruta, nrc.id_concepto_convenio_contrato, nrc.cantidad,
nc.codigo as convenio, ncon.nombre as concepto, nccc.codigo
from nov_ruta_concepto nrc
join nov_rutas nr on nr.id_ruta = nrc.id_ruta
join nov_convenios nc on nc.id_convenio = nr.id_convenio
join nov_concepto_convenio_contrato nccc on nccc.id_concepto_convenio_contrato = nrc.id_concepto_convenio_contrato
join nov_conceptos ncon on ncon.id_concepto = nccc.id_concepto
where nrc.id_ruta = :id_ruta";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_ruta', $id_ruta);
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


    function save(){
        if($this->id_vehiculo_contrato)
        {$rta = $this->updateVehiculoContrato();}
        else
        {$rta =$this->insertVehiculoContrato();}
        return $rta;
    }



    public function updateVehiculoContrato(){

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

    public function insertVehiculoContrato(){

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

    public function deleteVehiculoContrato(){
        $stmt=new sQuery();
        $query="delete from vto_vehiculo_contrato where id_vehiculo_contrato= :id_vehiculo_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo_contrato', $this->getIdVehiculoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkVehiculo($id_vehiculo, $id_contrato, $id_contrato_vehiculo) {
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