<?php

class EvaluacionCompetencia
{
    private $id_evaluacion_competencia;
    private $id_competencia;
    private $id_puntaje;
    private $fecha;
    private $id_evaluador;
    private $id_empleado;
    private $id_plan_evaluacion;
    private $periodo;


    // GETTERS
    function getIdEvaluacionCompetencia()
    { return $this->id_evaluacion_competencia;}

    function getIdCompetencia()
    { return $this->id_competencia;}

    function getIdPuntaje()
    { return $this->id_puntaje;}

    function getFecha()
    { return $this->fecha;}

    function getIdEvaluador()
    { return $this->id_evaluador;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getIdPlanEvaluacion()
    { return $this->id_plan_evaluacion;}

    function getPeriodo()
    { return $this->periodo;}



    //SETTERS
    function setIdEvaluacionCompetencia($val)
    { $this->id_evaluacion_competencia=$val;}

    function setIdCompetencia($val)
    { $this->id_competencia=$val;}

    function setIdPuntaje($val)
    { $this->id_puntaje=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIdEvaluador($val)
    {  $this->id_evaluador=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setIdPlanEvaluacion($val)
    { $this->id_plan_evaluacion=$val;}

    function setPeriodo($val)
    { $this->periodo=$val;}


    /*public static function getEvaluacionesCompetencias($id_empleado, $periodo) { //ok
        $stmt=new sQuery();
        $query="select *
from eac_evaluacion_competencia
where id_empleado = :id_empleado
and periodo = :periodo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }*/


    public static function getCompetencias($id_empleado, $periodo) { //ok
        $stmt=new sQuery();
        /*$query="select cnc.id_nivel_competencia, co.id_competencia, co.nombre,
eac_ec.id_evaluacion_competencia, eac_pu.nro_orden
from empleados em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join puestos pu on ec.id_puesto = pu.id_puesto
join competencias_niveles nc on pu.id_nivel_competencia = nc.id_nivel_competencia
join competencia_nivel_competencia cnc on cnc.id_nivel_competencia = nc.id_nivel_competencia
join competencias co on cnc.id_competencia = co.id_competencia
left join eac_evaluacion_competencia eac_ec on co.id_competencia = eac_ec.id_competencia and eac_ec.id_empleado = em.id_empleado and eac_ec.periodo = :periodo
left join eac_puntajes eac_pu on eac_ec.id_puntaje = eac_pu.id_puntaje
where em.id_empleado = :id_empleado";*/
        $query="select cnc.id_nivel_competencia, co.id_competencia, co.nombre,
eac_ec.id_evaluacion_competencia, eac_pu.nro_orden
from empleados em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join puestos pu on ec.id_puesto = pu.id_puesto
-- join competencias_niveles nc on pu.id_nivel_competencia = nc.id_nivel_competencia
join competencia_nivel_competencia cnc on pu.id_nivel_competencia = cnc.id_nivel_competencia
join competencias co on cnc.id_competencia = co.id_competencia
left join eac_evaluacion_competencia eac_ec on co.id_competencia = eac_ec.id_competencia and eac_ec.id_empleado = em.id_empleado and eac_ec.periodo = :periodo
left join eac_puntajes eac_pu on eac_ec.id_puntaje = eac_pu.id_puntaje
where em.id_empleado = :id_empleado
group by co.id_competencia";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getCompetencias1($id_empleado, $periodo) { //ok
        $stmt=new sQuery();

        $query="select null as id_nivel_competencia,
eac_ec.id_competencia,
co.nombre,
eac_ec.id_evaluacion_competencia,
eac_pu.nro_orden
from empleados em
join eac_evaluacion_competencia eac_ec on em.id_empleado = eac_ec.id_empleado and eac_ec.periodo = :periodo
join eac_puntajes eac_pu on eac_ec.id_puntaje = eac_pu.id_puntaje
join competencias co on eac_ec.id_competencia = co.id_competencia
where em.id_empleado = :id_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from eac_evaluacion_competencia where id_evaluacion_competencia = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEvaluacionCompetencia($rows[0]['id_evaluacion_competencia']);
            $this->setIdCompetencia($rows[0]['id_competencia']);
            $this->setIdPuntaje($rows[0]['id_puntaje']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdEvaluador($rows[0]['id_evaluador']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdPlanEvaluacion($rows[0]['id_plan_evaluacion']);
            $this->setPeriodo($rows[0]['periodo']);

        }
    }



    function save(){ //ok
        if($this->id_evaluacion_competencia)
        {$rta = $this->updateEvaluacionCompetencia();}
        else
        {$rta =$this->insertEvaluacionCompetencia();}
        return $rta;
    }


    public function updateEvaluacionCompetencia(){ //ok

        $stmt=new sQuery();
        $query="update eac_evaluacion_competencia set
                id_puntaje= :id_puntaje,
                id_evaluador=  :id_evaluador
                where id_evaluacion_competencia = :id_evaluacion_competencia";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puntaje', $this->getIdPuntaje());
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':id_evaluacion_competencia', $this->getIdEvaluacionCompetencia());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertEvaluacionCompetencia(){ //ok

        $stmt=new sQuery();
        $query="insert into eac_evaluacion_competencia(id_competencia, id_puntaje, fecha, id_evaluador, id_empleado, id_plan_evaluacion, periodo)
                values(:id_competencia, :id_puntaje, date(sysdate()), :id_evaluador, :id_empleado, :id_plan_evaluacion, :periodo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_competencia', $this->getIdCompetencia());
        $stmt->dpBind(':id_puntaje', $this->getIdPuntaje());
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_plan_evaluacion', $this->getIdPlanEvaluacion());
        $stmt->dpBind(':periodo', $this->getPeriodo());
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


    public static function getPuntajes() { //ok
        $stmt=new sQuery();
        $query="select *
                from eac_puntajes";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getPuntajeCompetencia() {
        $stmt=new sQuery();
        $query="select *
from
eac_puntaje_competencia
order by id_competencia, id_puntaje asc";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>