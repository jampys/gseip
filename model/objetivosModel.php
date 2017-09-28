<?php


class Objetivo
{
    private $id_objetivo;
    private $periodo;
    private $nombre;
    private $id_proceso;
    private $id_area;
    private $id_contrato;
    private $meta;
    private $actividades;
    private $indicador;
    private $frecuencia;
    private $id_responsable_ejecucion;
    private $id_responsable_seguimiento;


    // GETTERS
    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getPeriodo()
    { return $this->periodo;}

    function getNombre()
    { return $this->nombre;}

    function getIdProceso()
    { return $this->id_proceso;}

    function getIdArea()
    { return $this->id_area;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getMeta()
    { return $this->meta;}

    function getActividades()
    { return $this->actividades;}

    function getIndicador()
    { return $this->indicador;}

    function getFrecuencia()
    { return $this->frecuencia;}

    function getIdResponsableEjecucion()
    { return $this->id_responsable_ejecucion;}

    function getIdResponsableSeguimiento()
    { return $this->id_responsable_seguimiento;}


    //SETTERS
    function setIdObjetivo($val)
    { $this->id_objetivo=$val;}

    function setPeriodo($val)
    { $this->periodo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setIdProceso($val)
    { $this->id_proceso=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setMeta($val)
    { $this->meta=$val;}

    function setActividades($val)
    { $this->actividades=$val;}

    function setIndicador($val)
    { $this->indicador=$val;}

    function setFrecuencia($val)
    { $this->frecuencia=$val;}

    function setIdResponsableEjecucion($val)
    { $this->id_responsable_ejecucion=$val;}

    function setIdResponsableSeguimiento($val)
    { $this->id_responsable_seguimiento=$val;}


    public static function getObjetivos($periodo) { //ok
        $stmt=new sQuery();
        $query="select ob.*, ar.nombre as area, pro.nombre as proceso, cia.razon_social as contrato
from objetivos ob
left join areas ar on ob.id_area = ar.id_area
left join procesos pro on pro.id_proceso = ob.id_proceso
left join contratos con on con.id_contrato = ob.id_contrato
left join companias cia on cia.id_compania = con.id_compania
where ob.periodo = :periodo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from objetivos where id_objetivo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setPeriodo($rows[0]['periodo']);
            $this->setNombre($rows[0]['nombre']);
            $this->setIdProceso($rows[0]['id_proceso']);
            $this->setIdArea($rows[0]['id_area']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setMeta($rows[0]['meta']);
            $this->setActividades($rows[0]['actividades']);
            $this->setIndicador($rows[0]['indicador']);
            $this->setFrecuencia($rows[0]['frecuencia']);
            $this->setIdResponsableEjecucion($rows[0]['id_responsable_ejecucion']);
            $this->setIdResponsableSeguimiento($rows[0]['id_responsable_seguimiento']);
        }
    }



    function save(){
        if($this->id_objetivo)
        {$rta = $this->updateObjetivo();}
        else
        {$rta =$this->insertObjetivo();}
        return $rta;
    }

    public function updateObjetivo(){

        $stmt=new sQuery();
        $query="update objetivos set
                nombre= :nombre,
                tipo= :tipo,
                objetivo_superior= :objetivo_superior
                where id_objetivo = :id_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':objetivo_superior', $this->getObjetivoSuperior());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertObjetivo(){

        $stmt=new sQuery();
        $query="insert into objetivos(nombre, tipo, objetivo_superior)
                values(:nombre, :tipo, :objetivo_superior)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':objetivo_superior', $this->getObjetivoSuperior());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteObjetivo(){
        $stmt=new sQuery();
        $query="delete from objetivos where id_objetivo= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public static function getPeriodos() { //ok
        $stmt=new sQuery();
        $query = "select periodo
                  from objetivos
                  group by periodo";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public function autocompletarObjetivos($term) { //ok
        $stmt=new sQuery();
        /*$query = "select id_objetivo_puesto, op.id_objetivo, ob.nombre, id_contrato, periodo, op.valor
from objetivo_puesto op, objetivos ob
where op.id_objetivo = ob.id_objetivo
and nombre like '%$term%'
UNION
select null, id_objetivo, nombre, null, null, null
from objetivos
where nombre like '%$term%'";*/
        $query = "select * from objetivos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

}



?>