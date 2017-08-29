<?php
include_once("empleadosModel.php");

class Contrato
{
    private $id_contrato;
    private $nro_contrato;
    private $fecha_desde;
    private $fecha_hasta;
    private $id_responsable;
    private $id_compania;

    private $responsable;

    //GETTERS
    function getIdContrato()
    { return $this->id_contrato;}

    function getNroContrato()
    { return $this->nro_contrato;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getIdResponsable()
    { return $this->id_responsable;}

    function getIdCompania()
    { return $this->id_compania;}

    function getResponsable(){
        return ($this->responsable)? $this->responsable : new Empleado() ;
    }


    //SETTERS
    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setNroContrato($val)
    { $this->nro_contrato=$val;}

    function setFechaDesde($val)
    { $this->fecha_desde=$val;}

    function setFechaHasta($val)
    { $this->fecha_hasta=$val;}

    function setIdResponsable($val)
    { $this->id_responsable=$val;}

    function setIdCompania($val)
    { $this->id_compania=$val;}



    function Contrato($id_contrato = 0){ //constructor ok

        if ($id_contrato!= 0){

            $stmt=new sQuery();
            $query="select co.id_contrato, co.nro_contrato,
                    DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                    re.apellido, re.nombre, cia.razon_social,
                    co.id_responsable, co.id_compania
                    from contratos co, empleados re, companias cia
                    where co.id_responsable = re.id_empleado
                    and co.id_compania = cia.id_compania
                    and co.id_contrato = :id_contrato";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':id_contrato', $id_contrato);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setNroContrato($rows[0]['nro_contrato']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setIdResponsable($rows[0]['id_responsable']);
            $this->setIdCompania($rows[0]['id_compania']);

            $this->responsable = new Empleado($rows[0]['id_responsable']);

        }
    }


    public static function getContratos() { //ok
        $stmt=new sQuery();
        $query = "select id_contrato, nro_contrato,
                  DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  CONCAT(apellido, ' ', nombre) as responsable,
                  cia.razon_social as compania
                  from contratos co, empleados re, companias cia
                  where co.id_responsable = re.id_empleado
                  and co.id_compania = cia.id_compania";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public function getDomiciliosByEmpleado() {
        $id_empleado = $this->getIdEmpleado();
        $stmt=new sQuery();
        $query = "select dp.direccion,
                  loc.ciudad,
                  loc.CP,
                  loc.provincia,
                  loc.pais,
                  DATE_FORMAT(dp.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(dp.fecha_hasta,  '%d/%m/%Y') as fecha_hasta
                  from domicilios_particulares dp, localidades loc
                  where dp.id_localidad = loc.id_localidad
                  and fecha_hasta is not null
                  and dp.id_empleado = $id_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }





    function save($cambio_domicilio){
        if($this->id_empleado)
        {$rta = $this->updateEmpleado($cambio_domicilio);}
        else
        {$rta =$this->insertEmpleado();}
        return $rta;
    }

    public function updateEmpleado($cambio_domicilio){

        $stmt=new sQuery();
        /*$query="update empleados set legajo = :legajo, apellido=:apellido, nombre=:nombre, documento=:documento, cuil=:cuil,
                fecha_nacimiento= STR_TO_DATE(:fecha_nacimiento, '%d/%m/%Y'),
                fecha_alta= STR_TO_DATE(:fecha_alta, '%d/%m/%Y'),
                fecha_baja= STR_TO_DATE(:fecha_baja, '%d/%m/%Y'),
                domicilio =:domicilio, lugar_residencia=:lugar_residencia, telefono=:telefono, email=:email,
                sexo=:sexo, nacionalidad=:nacionalidad, estado_civil=:estado_civil, empresa=:empresa
                where id_empleado = :id_empleado";*/

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

        /*$stmt=new sQuery();
        $query="insert into empleados(legajo, apellido, nombre, documento, cuil, fecha_nacimiento, fecha_alta, fecha_baja, domicilio, lugar_residencia, telefono, email, sexo, nacionalidad, estado_civil, tipo)
                values(:legajo, :apellido, :nombre, :documento, :cuil,
                        STR_TO_DATE(:fecha_nacimiento, '%d/%m/%Y'),
                        STR_TO_DATE(:fecha_alta, '%d/%m/%Y'),
                        STR_TO_DATE(:fecha_baja, '%d/%m/%Y'),
                        :domicilio,
                        :lugar_residencia,
                        :telefono,
                        :email,
                        :sexo, :nacionalidad, :estado_civil, :empresa

                      )";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':legajo', $this->getLegajo());
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':documento', $this->getDocumento());
        $stmt->dpBind(':cuil', $this->getCuil());
        $stmt->dpBind(':fecha_nacimiento', $this->getFechaNacimiento());
        $stmt->dpBind(':fecha_alta', $this->getFechaAlta());
        $stmt->dpBind(':fecha_baja', $this->getFechaBaja());
        $stmt->dpBind(':domicilio', $this->getDomicilio());
        $stmt->dpBind(':lugar_residencia', $this->getLugarResidencia());
        $stmt->dpBind(':telefono', $this->getTelefono());
        $stmt->dpBind(':email', $this->getEmail());
        $stmt->dpBind(':sexo', $this->getSexo());
        $stmt->dpBind(':nacionalidad', $this->getNacionalidad());
        $stmt->dpBind(':estado_civil', $this->getEstadoCivil());
        $stmt->dpBind(':empresa', $this->getEmpresa());

        $stmt->dpExecute();
        return $stmt->dpGetAffect();*/

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



    public function checkEmpleadoCuil($cuil) {
        $stmt=new sQuery();
        $query = "select * from empleados
                  where cuil =:cuil and fecha_baja is null";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':cuil', $cuil);
        $stmt->dpExecute();

        //$stmt->dpFetchAll();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }

    public function checkEmpleadoLegajo($legajo) {
        $stmt=new sQuery();
        $query = "select * from empleados
                  where legajo = lpad(:legajo, 4, 0)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':legajo', $legajo);
        $stmt->dpExecute();

        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }


    public function autocompletarEmpleadosByCuil($term) {
        //Devuelve los empleados activos e inactivos agrupaddos por Cuil
        $stmt=new sQuery();
        $query = "select *
                  from empleados
                  where apellido like '%$term%'
                  or nombre like '%$term%'
                  group by cuil";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }






}

?>