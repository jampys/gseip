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



}



?>