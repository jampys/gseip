<?php

class Evaluacion
{
    //private $id_localidad;


    // GETTERS
    /*function getIdLocalidad()
    { return $this->id_localidad;}*/


    // SETTERS
    /*function setIdLocalidad($val)
    { $this->id_localidad=$val;}*/


    public static function getEvaluaciones($periodo, $id_contrato) { //ok
        //para planes de evaluacion ABIERTOS
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, ec.id_empleado_contrato, ec.id_contrato, ec.id_puesto,
co.nombre as contrato, pu.nombre as puesto,
pe.id_plan_evaluacion, pe.periodo, pe.cerrado,
func_eval_eac_count(em.id_empleado,pe.periodo) as hasAllEac,
func_eval_eaag_count(em.id_empleado,pe.periodo) as hasAllEaag,
func_es_inmediato_superior(em.id_empleado, ec.id_contrato) as isInSup
from v_sec_empleados_control em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join contratos co on ec.id_contrato = co.id_contrato
join companias cia on co.id_compania = cia.id_compania
join puestos pu on ec.id_puesto = pu.id_puesto
join ead_planes_evaluacion pe on pe.periodo = :periodo
where em.fecha_baja is null
and co.id_contrato = ifnull(:id_contrato, co.id_contrato)
group by em.id_empleado";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public static function getEvaluaciones1($periodo, $id_contrato) { //ok
        //para planes de evaluacion CERRADOS
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, ec.id_empleado_contrato, ec.id_contrato, ec.id_puesto,
co.nombre as contrato, pu.nombre as puesto,
pe.id_plan_evaluacion, pe.periodo, pe.cerrado,
(EXISTS (SELECT 1 FROM ead_evaluacion_competencia ead_ec where ead_ec.id_empleado = em.id_empleado and ead_ec.periodo = :periodo )) as hasAnyEac,
(EXISTS (SELECT 1 FROM ead_evaluacion_objetivo ead_eo where ead_eo.id_empleado = em.id_empleado and ead_eo.periodo = :periodo )) as hasAnyEao
from v_sec_empleados_control em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join contratos co on ec.id_contrato = co.id_contrato
join companias cia on co.id_compania = cia.id_compania
join puestos pu on ec.id_puesto = pu.id_puesto
join ead_evaluacion_competencia eec on em.id_empleado = eec.id_empleado
join ead_planes_evaluacion pe on eec.id_plan_evaluacion = pe.id_plan_evaluacion
where pe.periodo = :periodo
and co.id_contrato = ifnull(:id_contrato, co.id_contrato)
group by em.id_empleado";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public static function getPeriodos() { //ok
        $stmt=new sQuery();
        $query = "select periodo, cerrado
                  from ead_planes_evaluacion";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>