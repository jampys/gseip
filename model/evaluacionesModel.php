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


    public static function getEvaluaciones($periodo) { //ok
        $stmt=new sQuery();
        /*$query="select em.id_empleado, em.legajo, em.apellido, em.nombre, ec.id_empleado_contrato, ec.id_contrato, ec.id_puesto,
cia.razon_social as contrato, pu.nombre as puesto,
eac_ec.id_evaluacion_competencia, eao_eo.id_evaluacion_objetivo, pe.id_plan_evaluacion, pe.periodo
from empleados em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join contratos co on ec.id_contrato = co.id_contrato
join companias cia on co.id_compania = cia.id_compania
join puestos pu on ec.id_puesto = pu.id_puesto
left join eac_evaluacion_competencia eac_ec on em.id_empleado = eac_ec.id_empleado and eac_ec.periodo = :periodo
left join eao_evaluacion_objetivo eao_eo on em.id_empleado = eao_eo.id_empleado and eao_eo.periodo = :periodo
        left join planes_evaluacion pe on pe.id_plan_evaluacion = eac_ec.id_plan_evaluacion or pe.id_plan_evaluacion = eao_eo.id_plan_evaluacion";*/
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, ec.id_empleado_contrato, ec.id_contrato, ec.id_puesto,
co.nombre as contrato, pu.nombre as puesto,
pe.id_plan_evaluacion, pe.periodo,
(SELECT (EXISTS (SELECT 1 FROM eac_evaluacion_competencia eac_ec where eac_ec.id_empleado = em.id_empleado and eac_ec.periodo = :periodo ))) as hasAnyEac,
(SELECT (EXISTS (SELECT 1 FROM eao_evaluacion_objetivo eao_eo where eao_eo.id_empleado = em.id_empleado and eao_eo.periodo = :periodo ))) as hasAnyEao
from empleados em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join contratos co on ec.id_contrato = co.id_contrato
join companias cia on co.id_compania = cia.id_compania
join puestos pu on ec.id_puesto = pu.id_puesto
join planes_evaluacion pe on pe.periodo = :periodo";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getPeriodos() { //ok
        $stmt=new sQuery();
        $query = "select periodo
                  from planes_evaluacion";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>