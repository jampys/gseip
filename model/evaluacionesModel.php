<?php
include_once("empleadosModel.php");


class Evaluacion
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

    private $responsable_ejecucion;
    private $responsable_seguimiento;


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

    function getResponsableEjecucion(){
        return ($this->responsable_ejecucion)? $this->responsable_ejecucion : new Empleado() ;
    }

    function getResponsableSeguimiento(){
        return ($this->responsable_seguimiento)? $this->responsable_seguimiento : new Empleado() ;
    }


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


    public static function getEvaluaciones($periodo) { //ok
        $stmt=new sQuery();
        $query="select *
from empleados em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
left join evaluaciones ev on em.id_empleado = ev.id_empleado
left join planes_evaluacion pe on ev.id_plan_evaluacion = pe.id_plan_evaluacion and pe.periodo = :periodo
left join eac_evaluacion_competencia eac_ec on eac_ec.id_evaluacion = ev.id_evaluacion
left join eao_evaluacion_objetivo eao_eo on eao_eo.id_evaluacion = ev.id_evaluacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    function __construct($nro=0){ //constructor

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

            $this->responsable_ejecucion = new Empleado($rows[0]['id_responsable_ejecucion']);
            $this->responsable_seguimiento = new Empleado($rows[0]['id_responsable_seguimiento']);
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
                periodo= :periodo,
                nombre= :nombre,
                id_proceso= :id_proceso,
                id_area= :id_area,
                id_contrato= :id_contrato,
                meta= :meta,
                actividades= :actividades,
                indicador= :indicador,
                frecuencia= :frecuencia,
                id_responsable_ejecucion= :id_responsable_ejecucion,
                id_responsable_seguimiento= :id_responsable_seguimiento
                where id_objetivo = :id_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_proceso', $this->getIdProceso());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':meta', $this->getMeta());
        $stmt->dpBind(':actividades', $this->getActividades());
        $stmt->dpBind(':indicador', $this->getIndicador());
        $stmt->dpBind(':frecuencia', $this->getFrecuencia());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':id_responsable_seguimiento', $this->getIdResponsableSeguimiento());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertObjetivo(){

        $stmt=new sQuery();
        $query="insert into objetivos(periodo, nombre, id_proceso, id_area, id_contrato, meta, actividades, indicador, frecuencia, id_responsable_ejecucion, id_responsable_seguimiento)
                values(:periodo, :nombre, :id_proceso, :id_area, :id_contrato, :meta, :actividades, :indicador, :frecuencia, :id_responsable_ejecucion, :id_responsable_seguimiento)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_proceso', $this->getIdProceso());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':meta', $this->getMeta());
        $stmt->dpBind(':actividades', $this->getActividades());
        $stmt->dpBind(':indicador', $this->getIndicador());
        $stmt->dpBind(':frecuencia', $this->getFrecuencia());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':id_responsable_seguimiento', $this->getIdResponsableSeguimiento());
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

    public static function getPeriodos() {
        $stmt=new sQuery();
        $query = "select periodo
                  from objetivos
                  group by periodo";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public function autocompletarObjetivos($term) {
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