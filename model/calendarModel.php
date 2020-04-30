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
        /*$query = "select id_etapa, id_postulacion,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      DATE_FORMAT(fecha_etapa, '%d/%m/%Y') as fecha_etapa,
                      etapa, aplica, motivo, modo_contacto, comentarios, id_user
                      from sel_etapas
                      where id_etapa = :nro";*/
        $query = "SELECT descripcion as title, fecha as start, fecha as end
FROM tmp_calendar
where descripcion is not null
and fecha between '2020-04-30' and '2020-05-02'";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas las areas
    }





    public static function getSucesos($id_empleado, $eventos, $fecha_desde, $fecha_hasta, $id_contrato) { //ok
        $stmt=new sQuery();
        // el join con contratos activos, y el group
        $query = "select su.id_suceso, su.id_evento, su.id_empleado,
                  DATE_FORMAT(su.created_date,  '%d/%m/%Y') as created_date,
                  su.fecha_desde,
                  su.fecha_hasta,
                  su.observaciones,
                  CONCAT(em.apellido, ' ', em.nombre) as empleado,
                  ev.nombre as evento,
                  ev.codigo as txt_evento,
                  em.legajo as txt_legajo,
                  su.fecha_desde as txt_fecha_desde,
                  su.fecha_hasta as txt_fecha_hasta,
                  pe1.closed_date as closed_date_1,
                  if(pe2.created_date, pe2.closed_date, 1) as closed_date_2
                  from v_sec_nov_sucesos su
                  join empleados em on su.id_empleado = em.id_empleado
                  join nov_eventos_l ev on su.id_evento = ev.id_evento
                  left join empleado_contrato ec on su.id_empleado = ec.id_empleado and (ec.fecha_hasta is null or ec.fecha_hasta >= sysdate() )
                  join nov_periodos pe1 on pe1.id_periodo = su.id_periodo1
                  left join nov_periodos pe2 on pe2.id_periodo = su.id_periodo2
                  where su.id_empleado = ifnull(:id_empleado, su.id_empleado)
                  and su.id_evento in ($eventos)
                  and su.fecha_desde <= if(:fecha_desde is null, su.fecha_desde, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))
                  and su.fecha_hasta >= if(:fecha_hasta is null, su.fecha_hasta, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                  and if(:id_contrato is not null, ec.id_contrato = :id_contrato, (ec.id_contrato = ec.id_contrato or ec.id_contrato is null))
                  group by su.id_suceso";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        //$stmt->dpBind(':id_evento', $id_evento);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>