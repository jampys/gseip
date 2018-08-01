<?php


class Parte
{
    private $id_parte;
    private $fecha;
    private $fecha_parte;
    private $cuadrilla;
    private $id_area;
    private $id_vehiculo;
    private $id_evento;
    private $id_contrato;
    private $id_user;
    private $hs_normal;
    private $hs_50;
    private $hs_100;

    // GETTERS
    function getIdParte()
    { return $this->id_parte;}

    function getFecha()
    { return $this->fecha;}

    function getFechaParte()
    { return $this->fecha_parte;}

    function getCuadrilla()
    { return $this->cuadrilla;}

    function getIdArea()
    { return $this->id_area;}

    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getIdEvento()
    { return $this->id_evento;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getIdUser()
    { return $this->id_user;}

    function getHsNormal()
    { return $this->hs_normal;}

    function getHs50()
    { return $this->hs_50;}

    function getHs100()
    { return $this->hs_100;}



    //SETTERS
    function setIdParte($val)
    { $this->id_parte=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setFechaParte($val)
    { $this->fecha_parte=$val;}

    function setCuadrilla($val)
    {  $this->cuadrilla=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}

    function setIdVehiculo($val)
    {  $this->id_vehiculo=$val;}

    function setIdEvento($val)
    {  $this->id_evento=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}

    function setIdUser($val)
    {  $this->id_user=$val;}

    function setHsNormal($val)
    {  $this->hs_normal=$val;}

    function setHs50($val)
    {  $this->hs_50=$val;}

    function setHs100($val)
    {  $this->hs_100=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_parte,
                    DATE_FORMAT(fecha,  '%d/%m/%Y') as fecha,
                    DATE_FORMAT(fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    cuadrilla, id_area, id_vehiculo, id_evento, id_contrato, id_user,
                    hs_normal, hs_50, hs_100
                    from nov_partes where id_parte = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParte($rows[0]['id_parte']);
            $this->setFecha($rows[0]['fecha']);
            $this->setFechaParte($rows[0]['fecha_parte']);
            $this->setCuadrilla($rows[0]['cuadrilla']);
            $this->setIdArea($rows[0]['id_area']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdEvento($rows[0]['id_evento']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setHsNormal($rows[0]['hs_normal']);
            $this->setHs50($rows[0]['hs_50']);
            $this->setHs100($rows[0]['hs_100']);
        }
    }


    public static function getPartes($fecha_desde, $fecha_hasta, $id_contrato, $d) { //ok
        $stmt=new sQuery();
        $query="select pa.id_parte,
                    DATE_FORMAT(pa.fecha,  '%d/%m/%Y') as fecha,
                    DATE_FORMAT(pa.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    pa.cuadrilla, pa.id_area, pa.id_vehiculo, pa.id_evento, pa.id_contrato,
                    concat(ar.codigo, ' ', ar.nombre) as area,
                    concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                    concat(nec.codigo, ' ', nec.nombre) as evento,
                    co.nombre as contrato,
                    us.user
                    from nov_partes pa
                    left join nov_areas ar on pa.id_area = ar.id_area
                    left join vto_vehiculos ve on pa.id_vehiculo = ve.id_vehiculo
                    left join nov_eventos_c nec on pa.id_evento = nec.id_evento
                    left join contratos co on pa.id_contrato = co.id_contrato
                    join sec_users us on pa.id_user = us.id_user
                    and pa.fecha_parte between if(:fecha_desde is null, pa.fecha_parte, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                    and if(:fecha_hasta is null, pa.fecha_parte, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))
                    and pa.id_contrato =  ifnull(:id_contrato, pa.id_contrato)
                    order by pa.fecha_parte asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_parte)
        {$rta = $this->updateParte();}
        else
        {$rta =$this->insertParte();}
        return $rta;
    }


    public function updateParte(){

        $stmt=new sQuery();
        $query = 'CALL sp_updateEmpleados(:id_empleado,
                                        :legajo,
                                        :apellido,
                                        :nombre,
                                        :documento,
                                        :cuil,
                                        :fecha_nacimiento,
                                        :fecha_alta,
                                        :fecha_baja,
                                        :telefono,
                                        :email,
                                        :sexo,
                                        :nacionalidad,
                                        :estado_civil,
                                        :empresa,
                                        :direccion,
                                        :id_localidad,
                                        :cambio_domicilio,
                                        @flag
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':legajo', $this->getLegajo());
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':documento', $this->getDocumento());
        $stmt->dpBind(':cuil', $this->getCuil());
        $stmt->dpBind(':fecha_nacimiento', $this->getFechaNacimiento());
        $stmt->dpBind(':fecha_alta', $this->getFechaAlta());
        $stmt->dpBind(':fecha_baja', $this->getFechaBaja());
        $stmt->dpBind(':telefono', $this->getTelefono());
        $stmt->dpBind(':email', $this->getEmail());
        $stmt->dpBind(':sexo', $this->getSexo());
        $stmt->dpBind(':nacionalidad', $this->getNacionalidad());
        $stmt->dpBind(':estado_civil', $this->getEstadoCivil());
        $stmt->dpBind(':empresa', $this->getEmpresa());
        $stmt->dpBind(':direccion', $this->getDireccion());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpBind(':cambio_domicilio', $cambio_domicilio);

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : 0;
    }

    public function insertParte(){ //ok

        $stmt=new sQuery();
        $query="insert into nov_partes(fecha, fecha_parte, cuadrilla, id_area, id_vehiculo, id_evento, id_contrato, id_user)
                values(sysdate(), STR_TO_DATE(:fecha_parte, '%d/%m/%Y'), :cuadrilla, :id_area, :id_vehiculo, :id_evento, :id_contrato, :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_parte', $this->getFechaParte());
        $stmt->dpBind(':cuadrilla', $this->getCuadrilla());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deletePuesto(){
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function autocompletarPuestos($term) {
        $stmt=new sQuery();
        $query = "select *
                  from puestos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

}



?>