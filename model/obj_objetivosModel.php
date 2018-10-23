<?php

class Objetivo
{
    private $id_objetivo;
    private $periodo;
    private $nombre;
    //private $id_proceso;
    private $id_area;
    private $id_contrato;
    private $id_puesto;
    private $meta;
    private $meta_valor;
    private $indicador;
    private $frecuencia;
    private $id_responsable_ejecucion;
    private $id_responsable_seguimiento;
    private $codigo;

    // GETTERS
    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getPeriodo()
    { return $this->periodo;}

    function getNombre()
    { return $this->nombre;}

    function getIdArea()
    { return $this->id_area;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getIdPuesto()
    { return $this->id_puesto;}

    function getMeta()
    { return $this->meta;}

    function getMetaValor()
    { return $this->meta_valor;}

    function getIndicador()
    { return $this->indicador;}

    function getFrecuencia()
    { return $this->frecuencia;}

    function getIdResponsableEjecucion()
    { return $this->id_responsable_ejecucion;}

    function getIdResponsableSeguimiento()
    { return $this->id_responsable_seguimiento;}

    function getCodigo()
    { return $this->codigo;}


    //SETTERS
    function setIdObjetivo($val)
    { $this->id_objetivo=$val;}

    function setPeriodo($val)
    { $this->periodo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setIdPuesto($val)
    { $this->id_puesto=$val;}

    function setMeta($val)
    { $this->meta=$val;}

    function setMetaValor($val)
    { $this->meta_valor=$val;}

    function setIndicador($val)
    { $this->indicador=$val;}

    function setFrecuencia($val)
    { $this->frecuencia=$val;}

    function setIdResponsableEjecucion($val)
    { $this->id_responsable_ejecucion=$val;}

    function setIdResponsableSeguimiento($val)
    { $this->id_responsable_seguimiento=$val;}

    function setCodigo($val)
    { $this->codigo=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select * from obj_objetivos where id_objetivo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setPeriodo($rows[0]['periodo']);
            $this->setNombre($rows[0]['nombre']);
            $this->setIdArea($rows[0]['id_area']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setMeta($rows[0]['meta']);
            $this->setMetaValor($rows[0]['meta_valor']);
            $this->setIndicador($rows[0]['indicador']);
            $this->setFrecuencia($rows[0]['frecuencia']);
            $this->setIdResponsableEjecucion($rows[0]['id_responsable_ejecucion']);
            $this->setIdResponsableSeguimiento($rows[0]['id_responsable_seguimiento']);
            $this->setCodigo($rows[0]['codigo']);
        }
    }


    public static function getObjetivos($periodo) { //ok
        $stmt=new sQuery();
        $query = "select *
                  from v_sec_objetivos vso
                  where vso.periodo = ifnull(:periodo, vso.periodo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_objetivo)
        {$rta = $this->updateObjetivo();}
        else
        {$rta =$this->insertObjetivo();}
        return $rta;
    }


    public function updateObjetivo(){ //ok

        $stmt=new sQuery();
        $query="update obj_objetivos set
                periodo= :periodo,
                nombre= :nombre,
                id_area= :id_area,
                id_contrato= :id_contrato,
                id_puesto= :id_puesto,
                meta= :meta,
                meta_valor= :meta_valor,
                indicador= :indicador,
                frecuencia= :frecuencia,
                id_responsable_ejecucion= :id_responsable_ejecucion,
                id_responsable_seguimiento= :id_responsable_seguimiento
                where id_objetivo = :id_objetivo";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':meta', $this->getMeta());
        $stmt->dpBind(':meta_valor', $this->getMetaValor());
        $stmt->dpBind(':indicador', $this->getIndicador());
        $stmt->dpBind(':frecuencia', $this->getFrecuencia());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':id_responsable_seguimiento', $this->getIdResponsableSeguimiento());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertObjetivo(){ //ok

        $stmt=new sQuery();
        $query="insert into obj_objetivos(periodo, nombre, id_area, id_contrato, id_puesto, meta, meta_valor, indicador, frecuencia, id_responsable_ejecucion, id_responsable_seguimiento, fecha)
                values(:periodo, :nombre, :id_area, :id_contrato, :id_puesto, :meta, :meta_valor, :indicador, :frecuencia, :id_responsable_ejecucion, :id_responsable_seguimiento, SYSDATE())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':meta', $this->getMeta());
        $stmt->dpBind(':meta_valor', $this->getMetaValor());
        $stmt->dpBind(':indicador', $this->getIndicador());
        $stmt->dpBind(':frecuencia', $this->getFrecuencia());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':id_responsable_seguimiento', $this->getIdResponsableSeguimiento());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteObjetivo(){ //ok
        $stmt=new sQuery();
        $query="delete from obj_objetivos where id_objetivo= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function graficarGantt() { //ok
        $stmt=new sQuery();
        $query = "select t.id_tarea as Task_ID, t.nombre as Task_Name, t.nombre as Resource,
DATE_FORMAT(t.fecha_inicio,  '%Y-%m-%d') as Start_Date,
DATE_FORMAT(t.fecha_fin,  '%Y-%m-%d') as End_Date,
(select max(cantidad) from obj_avances where id_tarea = t.id_tarea) as Percent_Complete
from obj_tareas t
where id_objetivo = :id_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    /*public static function getPeriodos(){ //ok
        $stmt=new sQuery();
        $query = "select periodo
from obj_objetivos
group by periodo
UNION
select YEAR(CURDATE())"; //periodo actual (por si aun no existe un objetivo del periodo actual)
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }*/





}




?>