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


    public static function getEvaluaciones($periodo, $id_contrato, $id_puesto, $id_area) { //ok
        //para planes de evaluacion ABIERTOS
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre,
ec.id_empleado_contrato, ec.id_contrato, ec.id_puesto,
co.nombre as contrato,
pu.nombre as puesto,
pe.id_plan_evaluacion, pe.periodo, pe.cerrado,
func_eval_eac_count(em.id_empleado,pe.periodo) as hasAllEac,
func_eval_eaag_count(em.id_empleado,pe.periodo) as hasAllEaag,
func_eval_eao_count (em.id_empleado, pe.periodo) as hasAllEao,
func_es_inmediato_superior(em.id_empleado, ec.id_contrato) as isInSup
from v_sec_empleados_control em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join contratos co on ec.id_contrato = co.id_contrato
join companias cia on co.id_compania = cia.id_compania
join puestos pu on ec.id_puesto = pu.id_puesto
join ead_planes_evaluacion pe on pe.periodo = :periodo
where em.fecha_baja is null
and co.id_contrato = ifnull(:id_contrato, co.id_contrato)
and pu.id_puesto = ifnull(:id_puesto, pu.id_puesto)
and pu.id_area = ifnull(:id_area, pu.id_area)
group by em.id_empleado";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpBind(':id_area', $id_area);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public static function getEvaluaciones1($periodo, $id_contrato, $id_puesto, $id_area) { //ok
        //para planes de evaluacion CERRADOS
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, ec.id_empleado_contrato, ec.id_contrato, ec.id_puesto,
co.nombre as contrato, pu.nombre as puesto,
pe.id_plan_evaluacion, pe.periodo, pe.cerrado,
1 as hasAllEac,
1 as hasAllEaag,
1 as hasAllEao
from v_sec_empleados_control em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join contratos co on ec.id_contrato = co.id_contrato
join companias cia on co.id_compania = cia.id_compania
join puestos pu on ec.id_puesto = pu.id_puesto
join ead_planes_evaluacion pe on pe.periodo = :periodo
and
(
exists (select 1
            from ead_evaluacion_competencia eec
            where eec.id_empleado = em.id_empleado
            and eec.periodo = :periodo
           )
or exists (select 1
            from ead_evaluacion_aspecto_general eeag
            where eeag.id_empleado = em.id_empleado
            and eeag.periodo = :periodo
           )
or exists (select 1
            from ead_evaluacion_objetivo eeo
            where eeo.id_empleado = em.id_empleado
            and eeo.periodo = :periodo
           )


)
where co.id_contrato = ifnull(:id_contrato, co.id_contrato)
and pu.id_puesto = ifnull(:id_puesto, pu.id_puesto)
and pu.id_area = ifnull(:id_area, pu.id_area)
group by em.id_empleado";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpBind(':id_area', $id_area);
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


    public function graficarGauss($periodo, $id_contrato, $id_puesto, $id_area) { //ok
        //el query es similar a: getEvaluaciones()
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre,
ec.id_empleado_contrato, ec.id_contrato, ec.id_puesto,
co.nombre as contrato,
pu.nombre as puesto,
pe.id_plan_evaluacion, pe.periodo, pe.cerrado,
func_eval_puntaje_final(em.id_empleado, pe.periodo) as puntaje
from v_sec_empleados_control em
join empleado_contrato ec on em.id_empleado = ec.id_empleado
join contratos co on ec.id_contrato = co.id_contrato
join companias cia on co.id_compania = cia.id_compania
join puestos pu on ec.id_puesto = pu.id_puesto
join ead_planes_evaluacion pe on pe.periodo = :periodo
where em.fecha_baja is null
and co.id_contrato = ifnull(:id_contrato, co.id_contrato)
and pu.id_puesto = ifnull(:id_puesto, pu.id_puesto)
and pu.id_area = ifnull(:id_area, pu.id_area)
group by em.id_empleado";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpBind(':id_area', $id_area);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>