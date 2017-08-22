<?php


class Puesto
{
	private $id_puesto;
	private $nombre;
	private $descripcion;
	private $codigo;
	private $codigo_superior;

    // GETTERS
    function getIdPuesto()
    { return $this->id_puesto;}

    function getNombre()
    { return $this->nombre;}

    function getDescripcion()
    { return $this->descripcion;}

    function getCodigo()
    { return $this->codigo;}

    function getCodigoSuperior()
    { return $this->codigo_superior;}

    //SETTERS
    function setIdPuesto($val)
    { $this->id_puesto=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setDescripcion($val)
    { $this->descripcion=$val;}

    function setCodigo($val)
    {  $this->codigo=$val;}

    function setCodigoSuperior($val)
    {  $this->codigo_superior=$val;}


    public static function getPuestos() { //ok
			$stmt=new sQuery();
            $query="select pu.id_puesto, pu.nombre, pu.descripcion, pu.codigo, pu.codigo_superior, su.nombre as nombre_superior
                    from puestos pu
                    left join puestos su on pu.codigo_superior = su.codigo";
            $stmt->dpPrepare($query);
            $stmt->dpExecute();
            return $stmt->dpFetchAll();
		}

	function __construct($nro=0){ //constructor ok

		if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from puestos where id_puesto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setNombre($rows[0]['nombre']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setCodigo($rows[0]['codigo']);
            $this->setCodigoSuperior($rows[0]['codigo_superior']);
		}
	}
		


    function save(){ //ok
        if($this->id_puesto)
        {$rta = $this->updatePuesto();}
        else
        {$rta =$this->insertPuesto();}
        return $rta;
    }

	public function updatePuesto(){ //ok

        $stmt=new sQuery();
        $query="update puestos set
                nombre= :nombre,
                descripcion= :descripcion,
                codigo= :codigo,
                codigo_superior= :codigo_superior
                where id_puesto = :id_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':codigo_superior', $this->getCodigoSuperior());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	private function insertPuesto(){ //ok

        $stmt=new sQuery();
        $query="insert into puestos(nombre, descripcion, codigo, codigo_superior)
                values(:nombre, :descripcion, :codigo, :codigo_superior)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':codigo_superior', $this->getCodigoSuperior());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	function deletePuesto(){ //ok
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}


    public function autocompletarPuestos($term) { //ok
        $stmt=new sQuery();
        $query = "select *
                  from puestos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }
	
}
