<?php

class EvaluacionConclusion
{
    private $id_evaluacion_conclusion;
    private $fecha;
    private $id_evaluador;
    private $id_empleado;
    private $id_plan_evaluacion;
    private $periodo;
    private $fortalezas;
    private $aspectos_mejorar;


    // GETTERS
    function getIdEvaluacionConclusion()
    { return $this->id_evaluacion_conclusion;}

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

    function getFortalezas()
    { return $this->fortalezas;}

    function getAspectosMejorar()
    { return $this->aspectos_mejorar;}



    //SETTERS
    function setIdEvaluacionConclusion($val)
    { $this->id_evaluacion_conclusion=$val;}

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

    function setFortalezas($val)
    { $this->fortalezas=$val;}

    function setAspectosMejorar($val)
    { $this->aspectos_mejorar=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from ead_evaluacion_conclusion where id_evaluacion_conclusion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEvaluacionConclusion($rows[0]['id_evaluacion_conclusion']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdEvaluador($rows[0]['id_evaluador']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdPlanEvaluacion($rows[0]['id_plan_evaluacion']);
            $this->setPeriodo($rows[0]['periodo']);
            $this->setFortalezas($rows[0]['fortalezas']);
            $this->setAspectosMejorar($rows[0]['aspectos_mejorar']);

        }
    }



    function save(){ //ok
        if($this->id_evaluacion_conclusion)
        {$rta = $this->updateEvaluacionConclusion();}
        else
        {$rta =$this->insertEvaluacionConclusion();}
        return $rta;
    }


    public function updateEvaluacionConclusion(){ //ok

        $stmt=new sQuery();
        $query="update ead_evaluacion_conclusion set
                fortalezas = :fortalezas,
                aspectos_mejorar = :aspectos_mejorar,
                id_evaluador = :id_evaluador,
                fecha = sysdate()
                where id_evaluacion_conclusion = :id_evaluacion_conclusion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fortalezas', $this->getFortalezas());
        $stmt->dpBind(':aspectos_mejorar', $this->getAspectosMejorar());
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':id_evaluacion_conclusion', $this->getIdEvaluacionConclusion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertEvaluacionConclusion(){ //ok

        $stmt=new sQuery();
        $query="insert into ead_evaluacion_conclusion(fecha, id_evaluador, id_empleado, id_plan_evaluacion, periodo, fortalezas, aspectos_mejorar)
                values(sysdate(), :id_evaluador, :id_empleado, :id_plan_evaluacion, :periodo, :fortalezas, :aspectos_mejorar)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_plan_evaluacion', $this->getIdPlanEvaluacion());
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':fortalezas', $this->getFortalezas());
        $stmt->dpBind(':aspectos_mejorar', $this->getAspectosMejorar());
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


}



?>