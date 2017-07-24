<?php


class Empleado
{
    private $id_empleado;
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
            $query = "select id_empleado, apellido, nombre, documento, cuil,
                      DATE_FORMAT(fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                      DATE_FORMAT(fecha_alta,  '%d/%m/%Y') as fecha_alta,
                      DATE_FORMAT(fecha_baja,  '%d/%m/%Y') as fecha_baja,
                      domicilio, telefono, email, tipo,
                      lr.ciudad as lugar_residencia,
                      lugar_trabajo,
                      sexo, nacionalidad, estado_civil, CPA, legajo
                      from empleados em, localidades lr
                      where em.lugar_residencia = lr.id_localidad";
            $stmt->dpPrepare($query);
            $stmt->dpExecute();
            return $stmt->dpFetchAll();
		}

	function Empleado($id_empleado=0){ // declara el constructor, si trae el numero de cliente lo busca , si no, trae todos los clientes

		if ($id_empleado!=0){

            $stmt=new sQuery();
            $query="select legajo, nombre, apellido, DATE_FORMAT(fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento
                    from empleados where id_empleado = :id_empleado";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':id_empleado', $id_empleado);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setNombre($rows[0]['nombre']);
            $this->setApellido($rows[0]['apellido']);
            $this->setFechaNacimiento($rows[0]['fecha_nacimiento']);

		}
	}
		


    function save(){
        if($this->id)
        {$rta = $this->updateCliente();}
        else
        {$rta =$this->insertCliente();}
        return $rta;
    }

	public function updateCliente(){	// actualiza el cliente cargado en los atributos

        $stmt=new sQuery();
        $query="update clientes set nombre= :nombre, apellido= :apellido, fecha_nac= STR_TO_DATE(:fecha, '%d/%m/%Y'), peso= :peso where id = :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':fecha', $this->getFecha());
        $stmt->dpBind(':peso', $this->getPeso());
        $stmt->dpBind(':id', $this->getID());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	private function insertCliente(){	// inserta el cliente cargado en los atributos

        $stmt=new sQuery();
        $query="insert into clientes( nombre, apellido, fecha_nac,peso)values(:nombre, :apellido, STR_TO_DATE(:fecha, '%d/%m/%Y'), :peso)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':fecha', $this->getFecha());
        $stmt->dpBind(':peso', $this->getPeso());
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
function cleanString($string)
{
    $string=trim($string);
    $string=mysql_escape_string($string);
	$string=htmlspecialchars($string);
	
    return $string;
}