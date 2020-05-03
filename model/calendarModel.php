<?php

class Calendar
{
    private $id_area;
    private $nombre;

    // GETTERS
    function getIdArea()
    { return $this->id_area;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdArea($val)
    { $this->id_area=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getFeriados($start, $end) {
        $stmt=new sQuery();
        $query = "select 'feriado' as tipo_evento, descripcion as title, fecha as start, fecha as end, feriado as details
                  from tmp_calendar
                  where descripcion is not null
                  and fecha between :start and :end";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':start', $start);
        $stmt->dpBind(':end', $end);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }





    public static function getSucesos($empleados, $eventos, $fecha_desde, $fecha_hasta, $id_contrato) { //ok
        $empleados = ($empleados!='')? implode(",", $empleados)  : 'su.id_empleado';
        $stmt=new sQuery();
        $query = "select 'suceso' as tipo_evento, su.id_suceso, su.id_evento, su.id_empleado,
                  DATE_FORMAT(su.created_date,  '%d/%m/%Y') as created_date,
                  su.fecha_desde,
                  su.fecha_hasta,
                  su.observaciones,
                  CONCAT(em.apellido, ' ', em.nombre) as empleado,
                  ev.nombre as evento,
                  ev.codigo as txt_evento,
                  em.legajo as txt_legajo,
                  pe1.closed_date as closed_date_1,
                  if(pe2.created_date, pe2.closed_date, 1) as closed_date_2
                  from v_sec_nov_sucesos su
                  join empleados em on su.id_empleado = em.id_empleado
                  join nov_eventos_l ev on su.id_evento = ev.id_evento
                  left join empleado_contrato ec on su.id_empleado = ec.id_empleado and (ec.fecha_hasta is null or ec.fecha_hasta >= sysdate() )
                  join nov_periodos pe1 on pe1.id_periodo = su.id_periodo1
                  left join nov_periodos pe2 on pe2.id_periodo = su.id_periodo2
                  where su.id_empleado in ($empleados)
                  and su.id_evento in ($eventos)
                  and su.fecha_desde <= if(:fecha_desde is null, su.fecha_desde, :fecha_hasta)
                  and su.fecha_hasta >= if(:fecha_hasta is null, su.fecha_hasta, :fecha_desde)
                  and if(:id_contrato is not null, ec.id_contrato = :id_contrato, (ec.id_contrato = ec.id_contrato or ec.id_contrato is null))
                  group by su.id_suceso";

        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_empleado', $id_empleado);
        //$stmt->dpBind(':id_evento', $id_evento);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public static function getNovedadesEmpleado($empleados, $eventos, $fecha_desde, $fecha_hasta, $id_contrato) { //ok
        $empleados = ($empleados!='')? implode(",", $empleados)  : 'npe.id_empleado';
        $eventos = ($eventos!='')? implode(",", $eventos)  : 'null';
        $stmt=new sQuery();
        $query = "select npe.id_empleado,
CONCAT (em.apellido, ' ', em.nombre) as empleado,
np.id_parte, np.fecha_parte, np.cuadrilla, np.id_evento,
na.nombre as area,
nec.nombre as evento
from nov_partes np
join nov_parte_empleado npe on npe.id_parte = np.id_parte
join empleados em on em.id_empleado = npe.id_empleado
left join nov_areas na on na.id_area = np.id_area
left join nov_eventos_c nec on nec.id_evento = np.id_evento
where np.id_contrato = :id_contrato
and npe.id_empleado in ($empleados)
and np.fecha_parte between :fecha_desde and :fecha_hasta
and if(LENGTH($eventos)>0, np.id_evento in ($eventos), 1)
order by np.id_parte asc";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>