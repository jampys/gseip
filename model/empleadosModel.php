<?php

class Empleado
{
    private $id_empleado;
    private $legajo;
    private $apellido;
    private $nombre;
    private $documento;
    private $cuil;
    private $fecha_nacimiento;
    private $fecha_alta;
    private $fecha_baja;
    private $direccion;
    private $telefono;
    private $email;
    private $id_localidad;
    private $sexo;
    private $nacionalidad;
    private $estado_civil;
    private $cpa;
    private $empresa;
    private $domain;

    //GETTERS
    function getIdEmpleado()
    { return $this->id_empleado;}

    function getLegajo()
    { return $this->legajo;}

    function getApellido()
    { return $this->apellido;}

    function getNombre()
    { return $this->nombre;}

    function getDocumento()
    { return $this->documento;}

    function getCuil()
    { return $this->cuil;}

    function getFechaNacimiento()
    { return $this->fecha_nacimiento;}

    function getFechaAlta()
    { return $this->fecha_alta;}

    function getFechaBaja()
    { return $this->fecha_baja;}

    function getDireccion()
    { return $this->direccion;}

    function getTelefono()
    { return $this->telefono;}

    function getEmail()
    { return $this->email;}

    function getIdLocalidad()
    { return $this->id_localidad;}

    function getSexo()
    { return $this->sexo;}

    function getNacionalidad()
    { return $this->nacionalidad;}

    function getEstadoCivil()
    { return $this->estado_civil;}

    function getCpa()
    { return $this->cpa;}

    function getEmpresa()
    { return $this->empresa;}

    function getDomain()
    { return $this->domain;
    }

    //SETTERS
    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setLegajo($val)
    { $this->legajo=$val;}

    function setApellido($val)
    { $this->apellido=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setDocumento($val)
    { $this->documento=$val;}

    function setCuil($val)
    { $this->cuil=$val;}

    function setFechaNacimiento($val)
    { $this->fecha_nacimiento=$val;}

    function setFechaAlta($val)
    { $this->fecha_alta=$val;}

    function setFechaBaja($val)
    { $this->fecha_baja=$val;}

    function setDireccion($val)
    { $this->direccion=$val;}

    function setTelefono($val)
    { $this->telefono=$val;}

    function setEmail($val)
    { $this->email=$val;}

    function setIdLocalidad($val)
    { $this->id_localidad=$val;}

    function setSexo($val)
    { $this->sexo=$val;}

    function setNacionalidad($val)
    { $this->nacionalidad=$val;}

    function setEstadoCivil($val)
    { $this->estado_civil=$val;}

    function setCpa($val)
    { $this->cpa=$val;}

    function setEmpresa($val)
    { $this->empresa=$val;}

    function setDomain($val){
        if($val != '') $this->domain = explode(',',$val);
        else $this->domain = array();
    }



    function Empleado($id_empleado = 0){ //constructor

        if ($id_empleado!= 0){

            $stmt=new sQuery();
            $query="select em.id_empleado, em.legajo, em.apellido, em.nombre, em.documento, em.cuil,
                    DATE_FORMAT(em.fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                    DATE_FORMAT(em.fecha_alta,  '%d/%m/%Y') as fecha_alta,
                    DATE_FORMAT(em.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                    em.telefono, em.email, em.empresa,
                    em.sexo, em.nacionalidad, em.estado_civil,
                    dp.direccion, dp.id_localidad, domain
                    from empleados em
                    left join v_sec_domains vsd on em.id_object = vsd.id_object
                    join domicilios_particulares dp on em.id_empleado = dp.id_empleado
                    where dp.fecha_hasta is null
                    and em.id_empleado = :id_empleado";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':id_empleado', $id_empleado);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setLegajo($rows[0]['legajo']);
            $this->setApellido($rows[0]['apellido']);
            $this->setNombre($rows[0]['nombre']);
            $this->setDocumento($rows[0]['documento']);
            $this->setCuil($rows[0]['cuil']);
            $this->setFechaNacimiento($rows[0]['fecha_nacimiento']);
            $this->setFechaAlta($rows[0]['fecha_alta']);
            $this->setFechaBaja($rows[0]['fecha_baja']);
            $this->setDireccion($rows[0]['direccion']);
            $this->setTelefono($rows[0]['telefono']);
            $this->setEmail($rows[0]['email']);
            $this->setIdLocalidad($rows[0]['id_localidad']);
            $this->setSexo($rows[0]['sexo']);
            $this->setNacionalidad($rows[0]['nacionalidad']);
            $this->setEstadoCivil($rows[0]['estado_civil']);
            $this->setEmpresa($rows[0]['empresa']);
            $this->setDomain($rows[0]['domain']);

        }
    }


    public static function getEmpleados() {
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, em.documento, em.cuil,
                      DATE_FORMAT(em.fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                      DATE_FORMAT(em.fecha_alta,  '%d/%m/%Y') as fecha_alta,
                      DATE_FORMAT(em.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                      em.telefono, em.email, em.empresa,
                      em.sexo, em.nacionalidad, em.estado_civil, em.CPA,
                      loc.ciudad
                      from v_sec_empleados em, domicilios_particulares dp, localidades loc
                      where em.id_empleado = dp.id_empleado
                      and dp.fecha_hasta is null
                      and dp.id_localidad = loc.id_localidad";
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



    public function checkEmpleadoCuil($cuil, $id_empleado) {
        $stmt=new sQuery();
        $query = "select * from empleados em
                  where em.cuil =:cuil
                  and em.fecha_baja is null
                  and
                  ( -- nuevo empleado
                  :id_empleado is null
                  -- no se ponen condiciones
                  )
                  OR
                  ( -- edicion empleado
                  :id_empleado is not null
                  and em.id_empleado <> :id_empleado
                  )";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':cuil', $cuil);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpExecute();

        //$stmt->dpFetchAll();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }

    public function checkEmpleadoLegajo($legajo, $id_empleado) {
        $stmt=new sQuery();
        /*$query = "select * from empleados
                  where legajo = lpad(:legajo, 4, 0)";*/
        $query= "select * from empleados em
where
( -- nuevo empleado
:id_empleado is null
and em.legajo = :legajo
)
OR -- edicion empleado
(
:id_empleado is not null
and em.legajo = :legajo
and em.id_empleado <> :id_empleado
)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':legajo', $legajo);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpExecute();

        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }


    public function autocompletarEmpleadosByCuil($term) { //sera reemplazada por getEmpleadosActivos()
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