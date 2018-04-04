<?php


class Vehiculo
{
    private $id_vehiculo;
    private $nro_movil;
    private $matricula;
    private $marca;
    private $modelo;
    private $modelo_año;
    private $fecha_baja;
    private $id_contrato; //contrato al que esta afectado el vehiculo actualmente
    private $fecha_desde;
    private $fecha_hasta;

    // GETTERS
    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getNroMovil()
    { return $this->nro_movil;}

    function getMatricula()
    { return $this->matricula;}

    function getMarca()
    { return $this->marca;}

    function getModelo()
    { return $this->modelo;}

    function getModeloAño()
    { return $this->modelo_año;}

    function getFechaBaja()
    { return $this->fecha_baja;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    //SETTERS
    function setIdVehiculo($val)
    { $this->id_vehiculo=$val;}

    function setNroMovil($val)
    { $this->nro_movil=$val;}

    function setMatricula($val)
    { $this->matricula=$val;}

    function setMarca($val)
    {  $this->marca=$val;}

    function setModelo($val)
    {  $this->modelo=$val;}

    function setModeloAño($val)
    {  $this->modelo_año=$val;}

    function setFechaBaja($val)
    {  $this->fecha_baja=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}

    function setFechaDesde($val)
    {  $this->fecha_desde=$val;}

    function setFechaHasta($val)
    {  $this->fecha_hasta=$val;}


    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select ve.*, vvc.id_contrato,
                  DATE_FORMAT(vvc.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(vvc.fecha_hasta,  '%d/%m/%Y') as fecha_hasta
from vto_vehiculos ve
left join vto_vehiculo_contrato vvc on ve.id_vehiculo = vvc.id_vehiculo
where ve.id_vehiculo = :nro
and (vvc.fecha_hasta is null or datediff(vvc.fecha_hasta, date(sysdate())) > 0)";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setNroMovil($rows[0]['nro_movil']);
            $this->setMatricula($rows[0]['matricula']);
            $this->setMarca($rows[0]['marca']);
            $this->setModelo($rows[0]['modelo']);
            $this->setModeloAño($rows[0]['modelo_año']);
            $this->setFechaBaja($rows[0]['fecha_baja']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
        }
    }


    public static function getVehiculos() { //ok
        $stmt=new sQuery();
        $query="select ve.id_vehiculo, ve.nro_movil, ve.matricula, ve.marca, ve.modelo, ve.modelo_año, ve.propietario,
ve.fecha_baja, ve.responsable,
co.nombre as contrato
from vto_vehiculos ve
left join vto_vehiculo_contrato vvc on ve.id_vehiculo = vvc.id_vehiculo and vvc.fecha_hasta is not null
left join contratos co on vvc.id_contrato = co.id_contrato
order by ve.nro_movil asc";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getEmpleadosActivos() {
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, em.documento, em.cuil,
                      DATE_FORMAT(em.fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                      DATE_FORMAT(em.fecha_alta,  '%d/%m/%Y') as fecha_alta,
                      DATE_FORMAT(em.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                      em.telefono, em.email, em.empresa,
                      em.sexo, em.nacionalidad, em.estado_civil
                      from empleados em
                      where em.fecha_baja is null";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public function getContratosByVehiculo() {
        $stmt=new sQuery();
        $query = "select vvc.id_vehiculo_contrato, vvc.id_vehiculo, vvc.id_contrato,
co.nombre as contrato,
DATE_FORMAT(vvc.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(vvc.fecha_hasta,  '%d/%m/%Y') as fecha_hasta
from vto_vehiculo_contrato vvc
join contratos co on vvc.id_contrato = co.id_contrato
where vvc.fecha_hasta is not null
and id_vehiculo = :id_vehiculo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){
        if($this->id_puesto)
        {$rta = $this->updatePuesto();}
        else
        {$rta =$this->insertPuesto();}
        return $rta;
    }

    public function updateEmpleado($cambio_domicilio){

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

    private function insertEmpleado(){

        $stmt=new sQuery();
        $query = 'CALL sp_insertEmpleados(
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
                                        @flag
                                    )';

        $stmt->dpPrepare($query);

        //$stmt->dpBind(':id_empleado', $this->getIdEmpleado());
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
        //$stmt->dpBind(':cambio_domicilio', $cambio_domicilio);

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : 0;

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