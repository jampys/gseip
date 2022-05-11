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
DATE_FORMAT(cal.fecha,  '%d/%m/%Y') as fecha, cal.feriado, cal.descripcion as feriado_descripcion, cu.nombre as cuadrilla, cu.id_cuadrilla, cu.nombre_corto_op,
per.id_periodo, np.id_parte, np.comentarios, ar.nombre as area,
ev.nombre as evento,
(SELECT group_concat(concat(emx.apellido, ' ', emx.nombre) separator ' - ')
from nov_parte_empleado npex
join empleados emx on emx.id_empleado = npex.id_empleado
where npex.id_parte = np.id_parte
and npex.conductor = 1
order by emx.legajo asc) as conductor,
(SELECT group_concat(concat(emx.apellido, ' ', emx.nombre) separator ' - ')
from nov_parte_empleado npex
join empleados emx on emx.id_empleado = npex.id_empleado
where npex.id_parte = np.id_parte
and npex.conductor = 0
order by emx.legajo asc) as acompañante,
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
join nov_periodos per on per.id_periodo = :id_periodo
join v_tmp_calendar cal on cal.fecha between per.fecha_desde and per.fecha_hasta
left join nov_partes np on (np.id_contrato = cu.id_contrato and np.id_periodo = per.id_periodo and np.id_cuadrilla = cu.id_cuadrilla and np.fecha_parte = cal.fecha)
left join nov_areas ar on ar.id_area = np.id_area
left join nov_eventos_c ev on ev.id_evento = np.id_evento
where cu.id_contrato = :id_contrato
and cu.disabled is null
order by cu.nombre asc, cal.fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_periodo', $id_periodo);
        //$stmt->dpBind(':id_empleado', $id_empleado);
        //$stmt->dpBind(':id_concepto', $id_concepto);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public static function getReporteRn4Resumen($id_contrato, $id_periodo) { //ok
        //resumen de dias habiles trabajados por las cuadrillas
        $stmt=new sQuery();
        $query = "select np.cuadrilla, cu.nombre_corto_op,
count(*) as dht
from nov_partes np
join v_tmp_calendar cal on np.fecha_parte = cal.fecha
join nov_cuadrillas cu on cu.id_cuadrilla = np.id_cuadrilla
where np.id_contrato = :id_contrato
and np.id_periodo = :id_periodo
and np.id_cuadrilla is not null
and exists (select 1 from nov_parte_empleado npex where npex.id_parte = np.id_parte and npex.trabajado = 1)
and cal.feriado is null
and dayofweek(cal.fecha) in (2, 3, 4, 5, 6)
group by np.id_cuadrilla
order by cu.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_periodo', $id_periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }






    public static function getDaysBeetweenDates($fecha_desde, $fecha_hasta) { //ok
        //trae la cantidad de dias habiles entre 2 fechas
        $stmt=new sQuery();
        $query = "select count(*) as dias
                  from v_tmp_calendar cal
                  where cal.fecha between STR_TO_DATE(:fecha_desde, '%d/%m/%Y') and STR_TO_DATE(:fecha_hasta, '%d/%m/%Y')
                  and cal.feriado is null
                  and dayofweek(cal.fecha) in (2, 3, 4, 5, 6)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpExecute();
        $rows = $stmt ->dpFetchAll();
        return $rows[0]['dias'];
    }



    public static function getReporteRn3($id_contrato, $periodo) {
        $stmt=new sQuery();
        $query = "select em.legajo, em.apellido, em.nombre,
-- guardias
(select func_nov_cantidad('NOVEDAD', '1', :periodo, $id_contrato, em.id_empleado)) as guardias,
-- hs extras 50%
(select func_nov_cantidad('NOVEDAD', '2', :periodo, $id_contrato, em.id_empleado)) as hs_extras_50,
-- hs extras 50% por manejo
(select func_nov_cantidad('NOVEDAD', '6', :periodo, $id_contrato, em.id_empleado)) as hs_extras_50_manejo,
-- total hs extras 50%
(select func_nov_cantidad('NOVEDAD', '2,6', :periodo, $id_contrato, em.id_empleado)) as total_hs_extras_50,
-- hs extras 100%
(select func_nov_cantidad('NOVEDAD', '3', :periodo, $id_contrato, em.id_empleado)) as hs_extras_100,
-- hs base
(select func_nov_cantidad('NOVEDAD', '4', :periodo, $id_contrato, em.id_empleado)) as hs_base,
-- hs viaje
(select func_nov_cantidad('NOVEDAD', '5', :periodo, $id_contrato, em.id_empleado)) as hs_viaje,
-- total hs_viaje (hs_viaje + hs_base)
(select func_nov_cantidad('NOVEDAD', '4,5', :periodo, $id_contrato, em.id_empleado)) as total_hs_viaje,
-- viandas extras
(select func_nov_cantidad('NOVEDAD', '11', :periodo, $id_contrato, em.id_empleado)) as viandas_extra,
-- enfermedad
(select func_nov_cantidad('SUCESO', '17', :periodo, $id_contrato, em.id_empleado)) as enfermedad,
-- accidente
(select func_nov_cantidad('SUCESO', '15', :periodo, $id_contrato, em.id_empleado)) as accidente,
-- injustificadas
(select func_nov_cantidad('SUCESO', '33', :periodo, $id_contrato, em.id_empleado)) as injustificadas,
-- permiso_dias_tramite
(select func_nov_cantidad('SUCESO', '19', :periodo, $id_contrato, em.id_empleado)) as permiso_dias_tramite,
-- permiso_gremial
(select func_nov_cantidad('SUCESO', '34', :periodo, $id_contrato, em.id_empleado)) as permiso_gremial,
-- resto_permisos
(select func_nov_cantidad('SUCESO', '8,9,11,12,14,16,18,22', :periodo, $id_contrato, em.id_empleado)) as resto_permisos,
-- vacaciones
(select func_nov_cantidad('SUCESO', '21', :periodo, $id_contrato, em.id_empleado)) as vacaciones,
-- suspenciones
(select func_nov_cantidad('SUCESO', '32', :periodo, $id_contrato, em.id_empleado)) as suspenciones,
-- dias mayor funcion
(select func_nov_cantidad('NOVEDAD', '22', :periodo, $id_contrato, em.id_empleado)) as mayor_funcion,
-- dias mayor funcion. Motivo
(select func_nov_cantidadM('MAYOR_FUNCION', '22', :periodo, $id_contrato, em.id_empleado)) as mayor_funcion_m,
-- Dias Reales Trabajados (DRT)
(select func_nov_horas('DRT', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo)) as DRT,
-- Dias Habiles Trabajados (DHT)
(select func_nov_horas('DHT', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo)) as DHT,
-- Dias Habiles No Trabajados (DHNT)
(select func_nov_horas('DHNT', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo)) as DHNT,
-- Dias Corridos No Trabajados (DCNT)
(select func_nov_horas('DCNT', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo)) as DCNT,
-- Disponible en domicilio HABILES (DHDD)
(select func_nov_horas('DHDD', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo)) as DHDD,
-- Dias Habiles (DH)
(select func_nov_horas('DH', 'CAL', group_concat(ec.id_contrato), em.id_empleado, :periodo)) as DH
from empleados em
join empleado_contrato ec on ec.id_empleado = em.id_empleado
join nov_periodos p on (p.periodo = :periodo and p.id_contrato = ec.id_contrato)
where ec.id_contrato in($id_contrato)
and ec.fecha_desde <= p.fecha_hasta
and (ec.fecha_hasta is null or ec.fecha_hasta >= p.fecha_desde)
group by em.id_empleado
order by em.id_convenio asc, em.legajo asc";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>