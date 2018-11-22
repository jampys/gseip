<?php

class EvaluacionObjetivo
{
    private $id_evaluacion_objetivo;
    private $id_objetivo;
    private $id_puntaje_objetivo;
    private $fecha;
    private $id_evaluador;
    private $id_empleado;
    private $id_plan_evaluacion;
    private $periodo;


    // GETTERS
    function getIdEvaluacionObjetivo()
    { return $this->id_evaluacion_objetivo;}

    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getIdPuntajeObjetivo()
    { return $this->id_puntaje_objetivo;}

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
    function setIdEvaluacionObjetivo($val)
    { $this->id_evaluacion_objetivo=$val;}

    function setIdObjetivo($val)
    { $this->id_objetivo=$val;}

    function setIdPuntajeObjetivo($val)
    { $this->id_puntaje_objetivo=$val;}

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



    public static function getAspectosGenerales($id_empleado, $periodo) {
        //para planes abiertos (vigentes)
        $stmt=new sQuery();
        $query="select agnc.id_nivel_competencia, ag.id_aspecto_general, ag.nombre,
ead_eag.id_evaluacion_aspecto_general, ead_pag.puntaje
from empleados em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join puestos pu on ec.id_puesto = pu.id_puesto
join aspecto_general_nivel_competencia agnc on pu.id_nivel_competencia = agnc.id_nivel_competencia
join aspectos_generales ag on agnc.id_aspecto_general = ag.id_aspecto_general
left join ead_evaluacion_aspecto_general ead_eag on ag.id_aspecto_general = ead_eag.id_aspecto_general and ead_eag.id_empleado = em.id_empleado and ead_eag.periodo = :periodo
left join ead_puntaje_aspecto_general ead_pag on ead_eag.id_puntaje_aspecto_general = ead_pag.id_puntaje_aspecto_general
where em.id_empleado = :id_empleado
group by ag.id_aspecto_general";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getAspectosGenerales1($id_empleado, $periodo) {
        //para planes cerrados
        $stmt=new sQuery();
        $query="select null as id_nivel_competencia,
ead_eag.id_aspecto_general,
ag.nombre,
ead_eag.id_evaluacion_aspecto_general,
ead_pag.puntaje
from empleados em
join ead_evaluacion_aspecto_general ead_eag on em.id_empleado = ead_eag.id_empleado and ead_eag.periodo = :periodo
join ead_puntaje_aspecto_general ead_pag on ead_eag.id_puntaje_aspecto_general = ead_pag.id_puntaje_aspecto_general
join aspectos_generales ag on ead_eag.id_aspecto_general = ag.id_aspecto_general
where em.id_empleado = :id_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from ead_evaluacion_aspecto_general where id_evaluacion_aspecto_general = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEvaluacionAspectoGeneral($rows[0]['id_evaluacion_aspecto_general']);
            $this->setIdAspectoGeneral($rows[0]['id_aspecto_general']);
            $this->setIdPuntajeAspectoGeneral($rows[0]['id_puntaje_aspecto_general']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdEvaluador($rows[0]['id_evaluador']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdPlanEvaluacion($rows[0]['id_plan_evaluacion']);
            $this->setPeriodo($rows[0]['periodo']);

        }
    }



    function save(){
        if($this->id_evaluacion_aspecto_general)
        {$rta = $this->updateEvaluacionAspectoGeneral();}
        else
        {$rta =$this->insertEvaluacionAspectoGeneral();}
        return $rta;
    }


    public function updateEvaluacionAspectoGeneral(){

        $stmt=new sQuery();
        $query="update ead_evaluacion_aspecto_general set
                id_puntaje_aspecto_general = :id_puntaje_aspecto_general,
                id_evaluador=  :id_evaluador
                where id_evaluacion_aspecto_general = :id_evaluacion_aspecto_general";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puntaje_aspecto_general', $this->getIdPuntajeAspectoGeneral());
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':id_evaluacion_aspecto_general', $this->getIdEvaluacionAspectoGeneral());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertEvaluacionAspectoGeneral(){

        $stmt=new sQuery();
        $query="insert into ead_evaluacion_aspecto_general(id_aspecto_general, id_puntaje_aspecto_general, fecha, id_evaluador, id_empleado, id_plan_evaluacion, periodo)
                values(:id_aspecto_general, :id_puntaje_aspecto_general, date(sysdate()), :id_evaluador, :id_empleado, :id_plan_evaluacion, :periodo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_aspecto_general', $this->getIdAspectoGeneral());
        $stmt->dpBind(':id_puntaje_aspecto_general', $this->getIdPuntajeAspectoGeneral());
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


    public static function getPuntajes() {
        // obtengo los puntajes que tienen los aspectos generales
        $stmt=new sQuery();
        $query="select pag.id_puntaje_aspecto_general, pag.id_aspecto_general, pag.descripcion, ag.codigo, ag.nombre, pag.puntaje
                from ead_puntaje_aspecto_general pag
                join aspectos_generales ag on pag.id_aspecto_general = ag.id_aspecto_general
                order by ag.id_aspecto_general, pag.puntaje";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getPuntajesHelp() {
        //obtengo la descripcion de los puntajes de todas las competencias
        $stmt=new sQuery();
        $query="select pag.*, ag.nombre, ag.definicion
                from ead_puntaje_aspecto_general pag
                join aspectos_generales ag on pag.id_aspecto_general = ag.id_aspecto_general
                order by id_aspecto_general, puntaje desc";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function getDiasParo($id_empleado) {
        //funcion creada de manera temporal, hasta que desde el modulo de competencias se obtenga esta informacion
        $stmt=new sQuery();
        $query="select cantidad
from tmp_dias_paro
where id_empleado = :id_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>