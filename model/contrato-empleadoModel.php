<?php

class ContratoEmpleado
{
	private $id_empleado_contrato;
	private $id_empleado;
	private $id_contrato;
	private $id_puesto;
	private $fecha_desde;
    private $fecha_hasta;

    //GETTERS
	function getIdEmpleadoContrato()
	 { return $this->id_empleado_contrato;}

	function getIdEmpleado()
	 { return $this->id_empleado;}

	function getIdContrato()
	 { return $this->id_contrato;}

	function getIdPuesto()
	 { return $this->id_puesto;}

	function getFechaDesde()
	 { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}
	 
	//SETTERS
    function setIdEmpleadoContrato($val)
    { $this->id_empleado_contrato=$val;}

	function setIdEmpleado($val)
	 { $this->id_empleado=$val;}

	function setIdContrato($val)
	 {  $this->id_contrato=$val;}

	function setIdPuesto($val)
	 {  $this->id_puesto=$val;}

	function setFechaDesde($val)
	 {  $this->fecha_desde=$val;}

    function setFechaHasta($val)
    {  $this->fecha_hasta=$val;}



    function __construct($nro=0){ //constructor ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_empleado_contrato, id_empleado, id_contrato, id_puesto,
                    DATE_FORMAT(fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(fecha_hasta,  '%d/%m/%Y') as fecha_hasta
                    from empleado_contrato where id_empleado_contrato = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEmpleadoContrato($rows[0]['id_empleado_contrato']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
        }
    }

    //Devuelve todos los empleados de un determinado contrato
    public function getContratoEmpleado() { //ok
        $stmt=new sQuery();
        $query = "select ec.id_empleado_contrato, ec.id_empleado, ec.id_contrato, ec.id_puesto,
DATE_FORMAT(ec.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(ec.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
em.apellido, em.nombre,
pu.nombre as puesto
from empleado_contrato ec, empleados em, puestos pu
where ec.id_empleado = em.id_empleado
and ec.id_puesto = pu.id_puesto
and ec.id_contrato = :id_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    function save(){
        if($this->id)
        {$rta = $this->updateCliente();}
        else
        {$rta =$this->insertCliente();}
        return $rta;
    }

	public function updateCliente(){

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

	private function insertCliente(){

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

	function delete(){
        $stmt=new sQuery();
        $query="delete from clientes where id= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getID());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}	
	
}