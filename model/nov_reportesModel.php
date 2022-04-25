<?php


class ReporteNovedades
{


    public static function getReporteRn6($id_contrato, $id_periodo, $id_empleado, $id_concepto) { //ok
        $stmt=new sQuery();
        $query = "select DATE_FORMAT(np.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                  np.id_parte, np.cuadrilla, concat(em.legajo, ' ', em.apellido, ' ', em.nombre) as empleado,
                  nc.nombre as concepto, npec.cantidad, npec.motivo, nccc.codigo, nccc.variable, nconv.codigo as convenio,
                  concat(a.codigo, ' ', a.nombre) as area,
                  concat(e.codigo, ' ', e.nombre) as evento
                  from nov_partes np
                  join nov_parte_empleado npe on npe.id_parte = np.id_parte
                  join nov_parte_empleado_concepto npec on npec.id_parte_empleado = npe.id_parte_empleado
                  join empleados em on em.id_empleado = npe.id_empleado
                  join nov_concepto_convenio_contrato nccc on nccc.id_concepto_convenio_contrato = npec.id_concepto_convenio_contrato
                  join nov_conceptos nc on nc.id_concepto = nccc.id_concepto
                  join nov_convenios nconv on nconv.id_convenio = nccc.id_convenio
                  left join nov_areas a on a.id_area = np.id_area
                  left join nov_eventos_c e on e.id_evento = np.id_evento
                  where np.id_contrato = :id_contrato
                  and np.id_periodo = :id_periodo
                  and npe.id_empleado = ifnull(:id_empleado, npe.id_empleado)
                  and nccc.id_concepto_convenio_contrato = ifnull(:id_concepto, nccc.id_concepto_convenio_contrato)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_periodo', $id_periodo);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_concepto', $id_concepto);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getReporteRn4($id_contrato, $id_periodo, $id_empleado, $id_concepto) { //ok
        $stmt=new sQuery();
        $query = "select dayname(cal.fecha) as dia, dayofweek(cal.fecha) as dia_numero,
DATE_FORMAT(cal.fecha,  '%d/%m/%Y') as fecha, cal.feriado, cal.descripcion as feriado_descripcion, cu.nombre as cuadrilla, cu.id_cuadrilla, per.id_periodo, np.id_parte, np.comentarios, ar.nombre as area,
ev.nombre as evento,
(SELECT group_concat(concat(emx.apellido, ' ', emx.nombre) separator ' - ')
from nov_parte_empleado npex
join empleados emx on emx.id_empleado = npex.id_empleado
where npex.id_parte = np.id_parte
order by npex.conductor desc) as empleados,
null as hs_normal,
(select npex.trabajado
from nov_parte_empleado npex
where npex.id_parte = np.id_parte
order by npex.trabajado desc limit 1) as trabajado,
(select npecx.cantidad
from nov_parte_empleado npex
join nov_parte_empleado_concepto npecx on npecx.id_parte_empleado = npex.id_parte_empleado
join nov_concepto_convenio_contrato ncccx on ncccx.id_concepto_convenio_contrato = npecx.id_concepto_convenio_contrato
where npex.id_parte = np.id_parte
and ncccx.id_concepto = 2
order by npecx.cantidad desc
limit 1
) as hs_50,
(select npecx.cantidad
from nov_parte_empleado npex
join nov_parte_empleado_concepto npecx on npecx.id_parte_empleado = npex.id_parte_empleado
join nov_concepto_convenio_contrato ncccx on ncccx.id_concepto_convenio_contrato = npecx.id_concepto_convenio_contrato
where npex.id_parte = np.id_parte
and ncccx.id_concepto = 3
order by npecx.cantidad desc
limit 1
) as hs_100,
(SELECT group_concat(concat(npox.nro_parte_diario, ': ', npox.orden_nro, ' ', replace(npox.servicio, '\n', '')) separator ' - ')
from nov_parte_orden npox
where npox.id_parte = np.id_parte
) as detalle,
(select group_concat(if(npex.comentario is not null, concat(SUBSTRING_INDEX(emx.apellido, ' ', 1), ': ', replace(npex.comentario, '\n', '')), null) separator ' - ')
from nov_parte_empleado npex
join empleados emx on emx.id_empleado = npex.id_empleado
where npex.id_parte = np.id_parte) as comentarios_empleados
from nov_cuadrillas cu
join nov_periodos per on per.id_periodo = 301
join tmp_calendar cal on cal.fecha between per.fecha_desde and per.fecha_hasta
left join nov_partes np on (np.id_contrato = cu.id_contrato and np.id_periodo = per.id_periodo and np.id_cuadrilla = cu.id_cuadrilla and np.fecha_parte = cal.fecha)
left join nov_areas ar on ar.id_area = np.id_area
left join nov_eventos_c ev on ev.id_evento = np.id_evento
where cu.id_contrato = 21
and cu.disabled is null
order by cu.nombre asc, cal.fecha asc";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':id_periodo', $id_periodo);
        //$stmt->dpBind(':id_empleado', $id_empleado);
        //$stmt->dpBind(':id_concepto', $id_concepto);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>