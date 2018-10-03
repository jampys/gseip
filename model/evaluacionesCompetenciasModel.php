<?php

class EvaluacionCompetencia
{
    private $id_evaluacion_competencia;
    private $id_competencia;
    private $id_puntaje_competencia;
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

    function getIdPuntajeCompetencia()
    { return $this->id_puntaje_competencia;}

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

    function setIdPuntajeCompetencia($val)
    { $this->id_puntaje_competencia=$val;}

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
        //para planes abiertos (vigentes)
        $stmt=new sQuery();
        $query="select cnc.id_nivel_competencia, co.id_competencia, co.nombre,
ead_ec.id_evaluacion_competencia, ead_pc.puntaje
from empleados em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join puestos pu on ec.id_puesto = pu.id_puesto
join competencia_nivel_competencia cnc on pu.id_nivel_competencia = cnc.id_nivel_competencia
join competencias co on cnc.id_competencia = co.id_competencia
left join ead_evaluacion_competencia ead_ec on co.id_competencia = ead_ec.id_competencia and ead_ec.id_empleado = em.id_empleado and ead_ec.periodo = :periodo
-- left join eac_puntajes eac_pu on eac_ec.id_puntaje = eac_pu.id_puntaje
left join ead_puntaje_competencia ead_pc on ead_ec.id_puntaje_competencia = ead_pc.id_puntaje_competencia
where em.id_empleado = :id_empleado
group by co.id_competencia";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getCompetencias1($id_empleado, $periodo) { //ok
        //para planes cerrados
        $stmt=new sQuery();
        $query="select null as id_nivel_competencia,
ead_ec.id_competencia,
co.nombre,
ead_ec.id_evaluacion_competencia,
ead_pc.puntaje
from empleados em
join ead_evaluacion_competencia ead_ec on em.id_empleado = ead_ec.id_empleado and ead_ec.periodo = :periodo
-- join eac_puntajes eac_pu on eac_ec.id_puntaje = eac_pu.id_puntaje
join ead_puntaje_competencia ead_pc on ead_ec.id_puntaje_competencia = ead_pc.id_puntaje_competencia
join competencias co on ead_ec.id_competencia = co.id_competencia
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
            $query="select * from ead_evaluacion_competencia where id_evaluacion_competencia = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEvaluacionCompetencia($rows[0]['id_evaluacion_competencia']);
            $this->setIdCompetencia($rows[0]['id_competencia']);
            $this->setIdPuntajeCompetencia($rows[0]['id_puntaje_competencia']);
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
        $query="update ead_evaluacion_competencia set
                -- id_puntaje= :id_puntaje,
                id_puntaje_competencia = :id_puntaje_competencia,
                id_evaluador=  :id_evaluador
                where id_evaluacion_competencia = :id_evaluacion_competencia";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puntaje_competencia', $this->getIdPuntajeCompetencia());
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':id_evaluacion_competencia', $this->getIdEvaluacionCompetencia());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertEvaluacionCompetencia(){ //ok

        $stmt=new sQuery();
        $query="insert into ead_evaluacion_competencia(id_competencia, id_puntaje_competencia, fecha, id_evaluador, id_empleado, id_plan_evaluacion, periodo)
                values(:id_competencia, :id_puntaje_competencia, date(sysdate()), :id_evaluador, :id_empleado, :id_plan_evaluacion, :periodo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_competencia', $this->getIdCompetencia());
        $stmt->dpBind(':id_puntaje_competencia', $this->getIdPuntajeCompetencia());
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
        // obtengo los puntajes que tienen las competencias
        $stmt=new sQuery();
        /*$query="select *
                from eac_puntajes";*/
        $query="select pc.id_puntaje_competencia, pc.id_competencia, pc.descripcion, co.codigo, co.nombre, pc.puntaje
from ead_puntaje_competencia pc
join competencias co on pc.id_competencia = co.id_competencia
-- join eac_puntajes pu on pc.id_puntaje = pu.id_puntaje
order by co.id_competencia, pc.puntaje";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getPuntajeCompetencia() { //ok
        //obtengo la descripcion de un puntaje determinado para una competencia.
        $stmt=new sQuery();
        $query="select *
                from ead_puntaje_competencia
                order by id_competencia, puntaje asc";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>