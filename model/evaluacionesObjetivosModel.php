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
    private $ponderacion;


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

    function getPonderacion()
    { return $this->ponderacion;}



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

    function setPonderacion($val)
    { $this->ponderacion=$val;}



    public static function getObjetivos($id_empleado, $periodo) {
        //para planes abiertos (vigentes)
        $stmt=new sQuery();
        $query="select o.id_objetivo, o.periodo, o.nombre, o.id_area, o.id_contrato, id_puesto, o.meta, o.meta_valor, o.indicador,
o.frecuencia, o.id_responsable_ejecucion, o.id_responsable_seguimiento, o.fecha, o.codigo,
ead_eo.id_evaluacion_objetivo, ead_eo.ponderacion, ead_eo.id_puntaje_objetivo,
DATE_FORMAT(ead_eo.fecha, '%d/%m/%Y %H:%i') as fecha,
ead_po.puntaje,
us.user,
func_obj_progress(o.id_objetivo) as progreso
from obj_objetivos o
left join ead_evaluacion_objetivo ead_eo on ead_eo.id_objetivo = o.id_objetivo and o.id_responsable_ejecucion = ead_eo.id_empleado
left join ead_puntaje_objetivo ead_po on ead_eo.id_puntaje_objetivo = ead_po.id_puntaje_objetivo
left join sec_users us on us.id_user = ead_eo.id_evaluador
where o.id_responsable_ejecucion = :id_empleado
and o.periodo = :periodo";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    /*public static function getObjetivos1($id_empleado, $periodo) { //OBSOLETO 30/11/2018. Se pasa query a reporte
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
    }*/


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from ead_evaluacion_objetivo where id_evaluacion_objetivo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEvaluacionObjetivo($rows[0]['id_evaluacion_objetivo']);
            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setIdPuntajeObjetivo($rows[0]['id_puntaje_objetivo']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdEvaluador($rows[0]['id_evaluador']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdPlanEvaluacion($rows[0]['id_plan_evaluacion']);
            $this->setPeriodo($rows[0]['periodo']);
            $this->setPonderacion($rows[0]['ponderacion']);

        }
    }



    function save(){ //ok
        if($this->id_evaluacion_objetivo)
        {$rta = $this->updateEvaluacionObjetivo();}
        else
        {$rta =$this->insertEvaluacionObjetivo();}
        return $rta;
    }


    public function updateEvaluacionObjetivo(){ //ok

        $stmt=new sQuery();
        $query="update ead_evaluacion_objetivo set
                id_puntaje_objetivo = :id_puntaje_objetivo,
                id_evaluador = :id_evaluador,
                fecha = sysdate(),
                ponderacion = :ponderacion
                where id_evaluacion_objetivo = :id_evaluacion_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puntaje_objetivo', $this->getIdPuntajeObjetivo());
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':ponderacion', $this->getPonderacion());
        $stmt->dpBind(':id_evaluacion_objetivo', $this->getIdEvaluacionObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertEvaluacionObjetivo(){ //ok

        $stmt=new sQuery();
        $query="insert into ead_evaluacion_objetivo(id_objetivo, id_puntaje_objetivo, fecha, id_evaluador, id_empleado, id_plan_evaluacion, periodo, ponderacion)
                values(:id_objetivo, :id_puntaje_objetivo, sysdate(), :id_evaluador, :id_empleado, :id_plan_evaluacion, :periodo, :ponderacion)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpBind(':id_puntaje_objetivo', $this->getIdPuntajeObjetivo());
        $stmt->dpBind(':id_evaluador', $this->getIdEvaluador());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_plan_evaluacion', $this->getIdPlanEvaluacion());
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':ponderacion', $this->getPonderacion());
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
        // obtengo los puntajes que tienen los objetivos
        $stmt=new sQuery();
        /*$query="select pag.id_puntaje_aspecto_general, pag.id_aspecto_general, pag.descripcion, ag.codigo, ag.nombre, pag.puntaje
                from ead_puntaje_aspecto_general pag
                join aspectos_generales ag on pag.id_aspecto_general = ag.id_aspecto_general
                order by ag.id_aspecto_general, pag.puntaje";*/
        $query="select *
        from ead_puntaje_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getPuntajesHelp() { // NO HARIA FALTA
        //obtengo la descripcion de los puntajes de todos los objetivos
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
        //funcion creada ....
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