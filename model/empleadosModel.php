﻿<?php


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
	private $domicilio;
	private $telefono;
    private $email;

    private $lugar_residencia;
    private $lugar_trabajo;
    private $sexo;
    private $nacionalidad;
    private $estado_civil;
    private $cpa;

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

    function getDomicilio()
    { return $this->domicilio;}

    function getTelefono()
    { return $this->telefono;}

    function getEmail()
    { return $this->email;}

    function getLugarResidencia()
    { return $this->lugar_residencia;}

    function getLugarTrabajo()
    { return $this->lugar_trabajo;}

    function getSexo()
    { return $this->sexo;}

    function getNacionalidad()
    { return $this->nacionalidad;}

    function getEstadoCivil()
    { return $this->estado_civil;}

    function getCpa()
    { return $this->cpa;}

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

    function setDomicilio($val)
    { $this->domicilio=$val;}

    function setTelefono($val)
    { $this->telefono=$val;}

    function setEmail($val)
    { $this->email=$val;}

    function setLugarResidencia($val)
    { $this->lugar_residencia=$val;}

    function setLugarTrabajo($val)
    { $this->lugar_trabajo=$val;}

    function setSexo($val)
    { $this->sexo=$val;}

    function setNacionalidad($val)
    { $this->nacionalidad=$val;}

    function setEstadoCivil($val)
    { $this->estado_civil=$val;}

    function setCpa($val)
    { $this->cpa=$val;}


    public static function getEmpleados() {
			$stmt=new sQuery();
            $query = "select id_empleado, legajo, apellido, nombre, documento, cuil,
                      DATE_FORMAT(fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                      DATE_FORMAT(fecha_alta,  '%d/%m/%Y') as fecha_alta,
                      DATE_FORMAT(fecha_baja,  '%d/%m/%Y') as fecha_baja,
                      domicilio, telefono, email, tipo,
                      lr.ciudad as lugar_residencia,
                      lugar_trabajo,
                      sexo, nacionalidad, estado_civil, CPA
                      from empleados em, localidades lr
                      where em.lugar_residencia = lr.id_localidad";
            $stmt->dpPrepare($query);
            $stmt->dpExecute();
            return $stmt->dpFetchAll();
		}

	function Empleado($id_empleado = 0){ //constructor, si trae el numero de cliente lo busca , si no, trae todos los clientes

		if ($id_empleado!= 0){

            $stmt=new sQuery();
            $query="select id_empleado, legajo, apellido, nombre, documento, cuil,
                    DATE_FORMAT(fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                    DATE_FORMAT(fecha_alta,  '%d/%m/%Y') as fecha_alta,
                    DATE_FORMAT(fecha_baja,  '%d/%m/%Y') as fecha_baja,
                    domicilio, telefono, email, tipo,
                    lugar_residencia,
                    sexo, nacionalidad, estado_civil
                    from empleados where id_empleado = :id_empleado";
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
            $this->setDomicilio($rows[0]['domicilio']);
            $this->setTelefono($rows[0]['telefono']);
            $this->setEmail($rows[0]['email']);
            $this->setLugarResidencia($rows[0]['lugar_residencia']);
            $this->setSexo($rows[0]['sexo']);
            $this->setNacionalidad($rows[0]['nacionalidad']);
            $this->setEstadoCivil($rows[0]['estado_civil']);

		}
	}
		


    function save(){
        if($this->id_empleado)
        {$rta = $this->updateEmpleado();}
        else
        {$rta =$this->insertEmpleado();}
        return $rta;
    }

	public function updateEmpleado(){

        $stmt=new sQuery();
        $query="update empleados set legajo = :legajo, apellido=:apellido, nombre=:nombre, documento=:documento, cuil=:cuil,
                fecha_nacimiento= STR_TO_DATE(:fecha_nacimiento, '%d/%m/%Y'),
                fecha_alta= STR_TO_DATE(:fecha_alta, '%d/%m/%Y'),
                fecha_baja= STR_TO_DATE(:fecha_baja, '%d/%m/%Y'),
                domicilio =:domicilio, lugar_residencia=:lugar_residencia, telefono=:telefono, email=:email,
                sexo=:sexo, nacionalidad=:nacionalidad, estado_civil=:estado_civil
                where id_empleado = :id_empleado";

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
        $stmt->dpBind(':domicilio', $this->getDomicilio());
        $stmt->dpBind(':lugar_residencia', $this->getLugarResidencia());
        $stmt->dpBind(':telefono', $this->getTelefono());
        $stmt->dpBind(':email', $this->getEmail());
        $stmt->dpBind(':sexo', $this->getSexo());
        $stmt->dpBind(':nacionalidad', $this->getNacionalidad());
        $stmt->dpBind(':estado_civil', $this->getEstadoCivil());

        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	private function insertEmpleado(){

        $stmt=new sQuery();
        $query="insert into empleados(legajo, apellido, nombre, documento, cuil, fecha_nacimiento, fecha_alta, fecha_baja, domicilio, lugar_residencia, telefono, email, sexo, nacionalidad, estado_civil)
                values(:legajo, :apellido, :nombre, :documento, :cuil,
                        STR_TO_DATE(:fecha_nacimiento, '%d/%m/%Y'),
                        STR_TO_DATE(:fecha_alta, '%d/%m/%Y'),
                        STR_TO_DATE(:fecha_baja, '%d/%m/%Y'),
                        :domicilio,
                        :lugar_residencia,
                        :telefono,
                        :email,
                        :sexo, :nacionalidad, :estado_civil

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

        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	function delete(){	// elimina el cliente
        $stmt=new sQuery();
        $query="delete from clientes where id= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getID());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}




	
}


