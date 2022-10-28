<?php


class ReporteCapacitacion
{



    public static function getReporteRc01($periodo) {
        $stmt=new sQuery();
        /*$query = "select concat(em.legajo, ' ', em.apellido, ' ', em.nombre) as empleado
from empleados em
where em.fecha_baja is null
and exists ( -- que el empleado esté en algun contrato
select 1
from empleado_contrato ecx
where ecx.id_empleado = em.id_empleado
)
and not exists
(select 1
from cap_capacitacion_empleado cex
join cap_capacitaciones cx on cx.id_capacitacion = cex.id_capacitacion
where cex.id_empleado = em.id_empleado
and cx.periodo = :periodo
)";*/
        $query="select em.*,
concat(em.legajo, ' ', em.apellido, ' ', em.nombre) as empleado,
group_concat(co.nombre order by co.nombre SEPARATOR ', ') as contratos
from empleados em
join empleado_contrato ec on (ec.id_empleado = em.id_empleado and (ec.fecha_hasta is null or ec.fecha_hasta >= sysdate()))
join contratos co on co.id_contrato = ec.id_contrato
where em.fecha_baja is null
and not exists
(select 1
from cap_capacitacion_empleado cex
join cap_capacitaciones cx on cx.id_capacitacion = cex.id_capacitacion
where cex.id_empleado = em.id_empleado
and cx.periodo = :periodo
)
group by em.id_empleado
order by co.nombre asc, em.legajo asc";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':periodo', $periodo);
        //$stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }







}



?>