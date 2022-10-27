<?php


class ReporteCapacitacion
{



    public static function getReporteRc01($periodo) {
        $stmt=new sQuery();
        $query = "select *
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
)";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':periodo', $periodo);
        //$stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }







}



?>