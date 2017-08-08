<?php


class HabilidadEmpleado
{
	private $id_habilidad;

    // GETTERS
    function getIdHabilidad()
    { return $this->id_habilidad;}



    //SETTERS
    function setIdHabilidad($val)
    { $this->id_habilidad=$val;}



    function __construct($nro=0){ //constructor

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select * from habilidades where id_habilidad = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdHabilidad($rows[0]['id_habilidad']);
            $this->setCodigo($rows[0]['codigo']);
            $this->setNombre($rows[0]['nombre']);
            $this->setTipo($rows[0]['tipo']);
        }
    }


    public static function getHabilidadEmpleado($cuil, $id_habilidad) {
			$stmt=new sQuery();
            $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, em.cuil,
		hab.id_habilidad, hab.nombre as habilidad,
		DATE_FORMAT(he.fecha_desde,  '%d/%m/%Y') as fecha_desde
from habilidad_empleado he, habilidades hab, empleados em
where he.id_empleado = em.id_empleado
and he.id_habilidad = hab.id_habilidad
and em.cuil = ifnull(:cuil, em.cuil)
and hab.id_habilidad = ifnull(:id_habilidad, hab.id_habilidad)";

            $stmt->dpPrepare($query);
            $stmt->dpBind(':cuil', $cuil);
            $stmt->dpBind(':id_habilidad', $id_habilidad);
            $stmt->dpExecute();
            return $stmt->dpFetchAll();
		}





    function save(){
        if($this->id_habilidad)
        {$rta = $this->updateHabilidad();}
        else
        {$rta =$this->insertHabilidad();}
        return $rta;
    }


	public function updateHabilidad(){
        $stmt=new sQuery();
        $query="update habilidades set codigo =:codigo, nombre =:nombre, tipo =:tipo
                where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	private function insertHabilidad(){
        $stmt=new sQuery();
        $query="insert into habilidades(codigo, nombre, tipo)
                values(:codigo, :nombre, :tipo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	function deleteHabilidad(){
        $stmt=new sQuery();
        $query="delete from habilidades where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}	
	
}
