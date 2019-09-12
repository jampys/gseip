<?php

class UsuarioRol
{
    private $id_user_role;
    private $id_user;
    private $id_role;
    private $fecha_desde;
    private $fecha_hasta;

    //GETTERS
    function getIdUserRole()
    { return $this->id_user_role;}

    function getIdUser()
    { return $this->id_user;}

    function getIdRole()
    { return $this->id_role;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}


    //SETTERS
    function setIdUserRole($val)
    { $this->id_user_role=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setIdRole($val)
    {  $this->id_role=$val;}

    function setFechaDesde($val)
    {  $this->fecha_desde=$val;}

    function setFechaHasta($val)
    {  $this->fecha_hasta=$val;}


    function __construct($nro=0){ //constructor //ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_user_role, id_user, id_role,
                    DATE_FORMAT(fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(fecha_hasta,  '%d/%m/%Y') as fecha_hasta
                    from sec_user_role where id_user_role = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdUserRole($rows[0]['id_user_role']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setIdRole($rows[0]['id_role']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
        }
    }

    //Devuelve todos los roles de un determinado usuario
    public static function getRoles($id_usuario) {
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