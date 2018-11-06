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
    private $progreso;

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

    function getProgreso()
    { return $this->progreso;}


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

    function setProgreso($val)
    { $this->progreso=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select obj.*, func_obj_progress(obj.id_objetivo) as progreso
                    from obj_objetivos obj where id_objetivo = :nro";
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
            $this->setProgreso($rows[0]['progreso']);
        }
    }


    public static function getObjetivos($periodo, $id_puesto, $id_area, $id_contrato, $indicador, $id_responsable_ejecucion, $id_responsable_seguimiento) { //ok
        $stmt=new sQuery();
        /*$query = "select *
                  from v_sec_objetivos vso
                  where vso.periodo = ifnull(:periodo, vso.periodo)";*/
        $query = "select vso.*, func_obj_progress(vso.id_objetivo) as progreso
                  from v_sec_objetivos vso
                  where vso.periodo = ifnull(:periodo, vso.periodo)
                  and if (:id_puesto is null, 1, vso.id_puesto = :id_puesto)
                  and if (:id_area is null, 1, vso.id_area = :id_area)
                  and if (:id_contrato is null, 1, vso.id_contrato = :id_contrato)
                  and vso.indicador = ifnull(:indicador, vso.indicador)
                  and vso.id_responsable_ejecucion = ifnull(:id_responsable_ejecucion, vso.id_responsable_ejecucion)
                  and vso.id_responsable_seguimiento = ifnull(:id_responsable_seguimiento, vso.id_responsable_seguimiento)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpBind(':id_area', $id_area);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':indicador', $indicador);
        $stmt->dpBind(':id_responsable_ejecucion', $id_responsable_ejecucion);
        $stmt->dpBind(':id_responsable_seguimiento', $id_responsable_seguimiento);

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