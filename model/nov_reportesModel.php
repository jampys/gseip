<?php


class ReporteNovedades
{






    public static function getPdf($id_contrato, $id_periodo) {
        $stmt=new sQuery();
        $query = "select np.fecha_parte, np.id_parte, np.cuadrilla, concat(em.legajo, ' ', em.apellido, ' ', em.nombre) as empleado,
nc.nombre as concepto, npec.cantidad, nccc.codigo, nccc.variable, nconv.codigo as convenio
from nov_partes np
join nov_parte_empleado npe on npe.id_parte = np.id_parte
join nov_parte_empleado_concepto npec on npec.id_parte_empleado = npe.id_parte_empleado
join empleados em on em.id_empleado = npe.id_empleado
join nov_concepto_convenio_contrato nccc on nccc.id_concepto_convenio_contrato = npec.id_concepto_convenio_contrato
join nov_conceptos nc on nc.id_concepto = nccc.id_concepto
join nov_convenios nconv on nconv.id_convenio = nccc.id_convenio
where np.id_contrato = 21
and np.id_periodo = 225";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':cuadrilla', $cuadrilla);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>