﻿<?php


class HabilidadPuesto
{
    private $id_habilidad_puesto;
	private $id_habilidad;
    private $id_puesto;
    private $requerida;

    // GETTERS
    function getIdHabilidadPuesto()
    { return $this->id_habilidad_puesto;}

    function getIdHabilidad()
    { return $this->id_habilidad;}

    function getIdPuesto()
    { return $this->id_puesto;}

    function getRequerida()
    { return $this->requerida;}


    //SETTERS
    function setIdHabilidadPuesto($val)
    { $this->id_habilidad_puesto=$val;}

    function setIdHabilidad($val)
    { $this->id_habilidad=$val;}

    function setIdPuesto($val)
    { $this->id_puesto=$val;}

    function setRequerida($val)
    { $this->requerida=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select * from habilidad_puesto where id_habilidad_puesto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdHabilidadPuesto($rows[0]['id_habilidad_puesto']);
            $this->setIdHabilidad($rows[0]['id_habilidad']);
            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setRequerida($rows[0]['requerida']);
        }
    }


    public static function getHabilidadPuesto($id_puesto, $id_habilidad) { //ok
			$stmt=new sQuery();
            $query = "select hp.id_habilidad_puesto, pu.id_puesto, pu.nombre as puesto, pu.codigo,
		              hab.id_habilidad, hab.nombre as habilidad,
		              hp.requerida
                      from habilidad_puesto hp, habilidades hab, puestos pu
                      where hp.id_puesto = pu.id_puesto
                      and hp.id_habilidad = hab.id_habilidad
                      and pu.id_puesto = ifnull(:id_puesto, pu.id_puesto)
                      and hab.id_habilidad = ifnull(:id_habilidad, hab.id_habilidad)";

            $stmt->dpPrepare($query);
            $stmt->dpBind(':id_puesto', $id_puesto);
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

	public function insertHabilidadEmpleado(){
        /*$stmt=new sQuery();
        $query="insert into habilidad_empleado(id_habilidad, id_empleado, fecha_desde)
                values(:id_habilidad, :id_empleado, '2015-02-02')";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpExecute();*/

        $stmt=new sQuery();
        $query = 'CALL sp_insertHabilidadEmpleado(
                                                    :id_habilidad,
                                                    :id_empleado,
                                                    @flag
                                                  )';

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : 0;


	}


	function deleteHabilidadEmpleado(){
        $stmt=new sQuery();
        $query="delete from habilidad_empleado
                where id_habilidad_empleado =:id_habilidad_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad_empleado', $this->getIdHabilidadEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}	
	
}