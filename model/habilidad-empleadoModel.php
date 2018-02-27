<?php

class HabilidadEmpleado
{
    private $id_habilidad_empleado;
    private $id_habilidad;
    private $id_empleado;
    private $fecha_desde;

    // GETTERS
    function getIdHabilidadEmpleado()
    { return $this->id_habilidad_empleado;}

    function getIdHabilidad()
    { return $this->id_habilidad;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getFechaDesde()
    { return $this->fecha_desde;}


    //SETTERS
    function setIdHabilidadEmpleado($val)
    { $this->id_habilidad_empleado=$val;}

    function setIdHabilidad($val)
    { $this->id_habilidad=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setFechaDesde($val)
    { $this->fecha_desde=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select * from habilidad_empleado where id_habilidad_empleado = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdHabilidadEmpleado($rows[0]['id_habilidad_empleado']);
            $this->setIdHabilidad($rows[0]['id_habilidad']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
        }
    }


    public static function getHabilidadEmpleado($cuil, $id_habilidad) { //ok
        $stmt=new sQuery();
        $query = "select id_habilidad_empleado, em.id_empleado, em.legajo, em.apellido, em.nombre, em.cuil,
		hab.id_habilidad, hab.nombre as habilidad,
		DATE_FORMAT(he.fecha_desde,  '%d/%m/%Y') as fecha_desde
from habilidad_empleado he, habilidades hab, v_sec_empleados em
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

    public function insertHabilidadEmpleado(){ //ok
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


    function deleteHabilidadEmpleado(){ //ok
        $stmt=new sQuery();
        $query="delete from habilidad_empleado
                where id_habilidad_empleado =:id_habilidad_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad_empleado', $this->getIdHabilidadEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

}

?>