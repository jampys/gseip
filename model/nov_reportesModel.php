<?php


class ReporteNovedades
{


    public static function getPdf($id_contrato, $id_periodo) { //ok
        $stmt=new sQuery();
        $query = "select DATE_FORMAT(np.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                  np.id_parte, np.cuadrilla, concat(em.legajo, ' ', em.apellido, ' ', em.nombre) as empleado,
                  nc.nombre as concepto, npec.cantidad, nccc.codigo, nccc.variable, nconv.codigo as convenio,
                  concat(a.codigo, ' ', a.nombre) as area
                  from nov_partes np
                  join nov_parte_empleado npe on npe.id_parte = np.id_parte
                  join nov_parte_empleado_concepto npec on npec.id_parte_empleado = npe.id_parte_empleado
                  join empleados em on em.id_empleado = npe.id_empleado
                  join nov_concepto_convenio_contrato nccc on nccc.id_concepto_convenio_contrato = npec.id_concepto_convenio_contrato
                  join nov_conceptos nc on nc.id_concepto = nccc.id_concepto
                  join nov_convenios nconv on nconv.id_convenio = nccc.id_convenio
                  left join nov_areas a on a.id_area = np.id_area
                  where np.id_contrato = :id_contrato
                  and np.id_periodo = :id_periodo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_periodo', $id_periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>