<?php


class Parte
{
    private $id_parte;
    private $fecha_parte;
    private $cuadrilla;
    private $id_area;
    private $id_vehiculo;
    private $id_evento;
    private $id_contrato;
    private $hs_normal;
    private $hs_50;
    private $hs_100;
    private $comentarios;
    private $created_by;
    private $created_date;
    private $id_periodo;
    private $id_cuadrilla;

    // GETTERS
    function getIdParte()
    { return $this->id_parte;}

    function getFechaParte()
    { return $this->fecha_parte;}

    function getCuadrilla()
    { return $this->cuadrilla;}

    function getIdArea()
    { return $this->id_area;}

    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getIdEvento()
    { return $this->id_evento;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getHsNormal()
    { return $this->hs_normal;}

    function getHs50()
    { return $this->hs_50;}

    function getHs100()
    { return $this->hs_100;}

    function getComentarios()
    { return $this->comentarios;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}

    function getIdPeriodo()
    { return $this->id_periodo;}

    function getIdCuadrilla()
    { return $this->id_cuadrilla;}



    //SETTERS
    function setIdParte($val)
    { $this->id_parte=$val;}

    function setFechaParte($val)
    { $this->fecha_parte=$val;}

    function setCuadrilla($val)
    {  $this->cuadrilla=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}

    function setIdVehiculo($val)
    {  $this->id_vehiculo=$val;}

    function setIdEvento($val)
    {  $this->id_evento=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}

    function setHsNormal($val)
    {  $this->hs_normal=$val;}

    function setHs50($val)
    {  $this->hs_50=$val;}

    function setHs100($val)
    {  $this->hs_100=$val;}

    function setComentarios($val)
    {  $this->comentarios=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}

    function setIdPeriodo($val)
    {  $this->id_periodo=$val;}

    function setIdCuadrilla($val)
    {  $this->id_cuadrilla=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_parte,
                    DATE_FORMAT(fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    cuadrilla, id_area, id_vehiculo, id_evento, id_contrato,
                    TIME_FORMAT(hs_normal, '%H:%i') as hs_normal,
                    TIME_FORMAT(hs_50, '%H:%i') as hs_50,
                    TIME_FORMAT(hs_100, '%H:%i') as hs_100,
                    comentarios, created_by,
                    DATE_FORMAT(created_date,  '%d/%m/%Y') as created_date,
                    id_periodo, id_cuadrilla
                    from nov_partes where id_parte = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParte($rows[0]['id_parte']);
            $this->setFechaParte($rows[0]['fecha_parte']);
            $this->setCuadrilla($rows[0]['cuadrilla']);
            $this->setIdArea($rows[0]['id_area']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdEvento($rows[0]['id_evento']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setHsNormal($rows[0]['hs_normal']);
            $this->setHs50($rows[0]['hs_50']);
            $this->setHs100($rows[0]['hs_100']);
            $this->setComentarios($rows[0]['comentarios']);
            $this->setCreatedDate($rows[0]['created_date']);
            $this->setIdPeriodo($rows[0]['id_periodo']);
            $this->setIdCuadrilla($rows[0]['id_cuadrilla']);
            $this->setCreatedBy($rows[0]['created_by']);
        }
    }


    public static function getPartes($fecha_desde, $fecha_hasta, $id_contrato, $id_periodo, $cuadrilla) { //ok
        $stmt=new sQuery();
        /*
         * $query="select pa.id_parte,
                    (select count(*) from nov_parte_orden npox where npox.id_parte = pa.id_parte) as orden_count,
                    (select count(*) from nov_parte_empleado_concepto npecx join nov_parte_empleado npex on npex.id_parte_empleado = npecx.id_parte_empleado where npex.id_parte = pa.id_parte) as concept_count,
                    DATE_FORMAT(pa.created_date,  '%d/%m/%Y') as created_date,
                    DATE_FORMAT(pa.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    pa.cuadrilla, pa.id_area, pa.id_vehiculo, pa.id_evento, pa.id_contrato, pa.last_calc_status,
                    concat(ar.codigo, ' ', ar.nombre) as area,
                    ve.nro_movil as vehiculo,
                    concat(nec.codigo, ' ', nec.nombre) as evento,
                    co.nombre as contrato,
                    us.user, pa.created_by,
                    pa.id_periodo, pe.closed_date
                    from nov_partes pa
                    left join nov_areas ar on pa.id_area = ar.id_area
                    left join vto_vehiculos ve on pa.id_vehiculo = ve.id_vehiculo
                    left join nov_eventos_c nec on pa.id_evento = nec.id_evento
                    join v_sec_contratos_control co on pa.id_contrato = co.id_contrato
                    join sec_users us on pa.created_by = us.id_user
                    join nov_periodos pe on pe.id_periodo = pa.id_periodo
                    and pa.fecha_parte between if(:fecha_desde is null, pa.fecha_parte, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                    and if(:fecha_hasta is null, pa.fecha_parte, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))
                    and pa.id_contrato =  ifnull(:id_contrato, pa.id_contrato)
                    and pa.id_periodo =  ifnull(:id_periodo, pa.id_periodo)
                    and pa.cuadrilla =  ifnull(:cuadrilla, pa.cuadrilla)
                    order by pa.fecha_parte asc";
         */
        $query="select pa.id_parte,
                    (select count(*) from nov_parte_orden npox where npox.id_parte = pa.id_parte) as orden_count,
                    (select count(*) from nov_parte_empleado_concepto npecx join nov_parte_empleado npex on npex.id_parte_empleado = npecx.id_parte_empleado where npex.id_parte = pa.id_parte) as concept_count,
                    DATE_FORMAT(pa.created_date,  '%d/%m/%Y') as created_date,
                    DATE_FORMAT(pa.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    pa.cuadrilla, pa.id_area, pa.id_vehiculo, pa.id_evento, pa.id_contrato, pa.last_calc_status,
                    concat(ar.codigo, ' ', ar.nombre) as area,
                    ve.nro_movil as vehiculo,
                    concat(nec.codigo, ' ', nec.nombre) as evento,
                    co.nombre as contrato,
                    us.user, pa.created_by,
                    pa.id_periodo, pe.closed_date
                    from nov_partes pa
                    left join nov_areas ar on pa.id_area = ar.id_area
                    left join vto_vehiculos ve on pa.id_vehiculo = ve.id_vehiculo
                    left join nov_eventos_c nec on pa.id_evento = nec.id_evento
                    join v_sec_contratos_control co on pa.id_contrato = co.id_contrato
                    join sec_users us on pa.created_by = us.id_user
                    join nov_periodos pe on pe.id_periodo = pa.id_periodo
                    and pa.fecha_parte between if(:fecha_desde is null, pa.fecha_parte, :fecha_desde)
                    and if(:fecha_hasta is null, pa.fecha_parte, :fecha_hasta)
                    and pa.id_contrato =  ifnull(:id_contrato, pa.id_contrato)
                    and pa.id_periodo =  ifnull(:id_periodo, pa.id_periodo)
                    and pa.cuadrilla =  ifnull(:cuadrilla, pa.cuadrilla)
                    order by pa.fecha_parte asc
                    limit 5000";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_periodo', $id_periodo);
        $stmt->dpBind(':cuadrilla', $cuadrilla);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_parte)
        {$rta = $this->updateParte();}
        else
        {$rta =$this->insertParte();}
        return $rta;
    }


    public function updateParte(){ //ok
        $stmt=new sQuery();
        $query = 'CALL sp_calcularNovedades2(:id_parte,
                                        :id_area,
                                        :id_vehiculo,
                                        :id_evento,
                                        :hs_normal,
                                        :hs_50,
                                        :hs_100,
                                        :created_by,
                                        @flag,
                                        @msg
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':hs_normal', $this->getHsNormal());
        $stmt->dpBind(':hs_50', $this->getHs50());
        $stmt->dpBind(':hs_100', $this->getHs100());
        $stmt->dpBind(':created_by', $this->getCreatedBy());

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag, @msg as msg";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        //$flag = $stmt->dpFetchAll();
        //return ($flag)? intval($flag[0]['flag']) : -1;
        return $stmt->dpFetchAll(); //retorna array bidimensional con flag y msg
    }


    public function updateParte2($id_parte_empleado, $id_empleado, $id_evento, $conductor, $comentario, $trabajado){
        //para novedades2.
        $stmt=new sQuery();
        $query = 'CALL sp_updateParte(:id_parte,
                                        :fecha_parte,
                                        :id_contrato,
                                        :id_area,
                                        :id_vehiculo,
                                        :id_cuadrilla,
                                        :id_periodo,
                                        :id_parte_empleado,
                                        :id_empleado,
                                        :id_evento,
                                        :conductor,
                                        :comentario,
                                        :trabajado,
                                        :created_by,
                                        @flag,
                                        @id_parte,
                                        @id_parte_empleado,
                                        @msg
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpBind(':fecha_parte', $this->getFechaParte());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_cuadrilla', $this->getIdCuadrilla());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_periodo', $this->getIdPeriodo());

        $stmt->dpBind(':id_parte_empleado', $id_parte_empleado);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_evento', $id_evento);
        $stmt->dpBind(':conductor', $conductor);
        $stmt->dpBind(':comentario', $comentario);
        $stmt->dpBind(':trabajado', $trabajado);

        $stmt->dpBind(':created_by', $this->getCreatedBy());

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag, @id_parte as id_parte, @id_parte_empleado as id_parte_empleado, @msg as msg";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        //$flag = $stmt->dpFetchAll();
        //return ($flag)? intval($flag[0]['flag']) : -1;
        return $stmt->dpFetchAll(); //retorna array bidimensional con flag y msg
    }






    public function insertParte(){ //ok

        $stmt=new sQuery();
        $query="insert into nov_partes(fecha_parte, id_cuadrilla, cuadrilla, id_area, id_vehiculo, id_evento, id_contrato, created_by, created_date, id_periodo)
                values(STR_TO_DATE(:fecha_parte, '%d/%m/%Y'), :id_cuadrilla, :cuadrilla, :id_area, :id_vehiculo, :id_evento, :id_contrato, :created_by, sysdate(), :id_periodo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_parte', $this->getFechaParte());
        $stmt->dpBind(':id_cuadrilla', $this->getIdCuadrilla());
        $stmt->dpBind(':cuadrilla', $this->getCuadrilla());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpBind(':id_periodo', $this->getIdPeriodo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public static function checkExportTxt($id_contrato, $periodo) { //ok
        $stmt=new sQuery();
        /*$query = 'CALL sp_nov_checkExportTxt(
                                    :id_contrato,
                                    :periodo,
                                    @flag,
                                    @msg)';*/
        $query = "CALL sp_nov_checkExportTxt(
                                    '$id_contrato',
                                    '$periodo',
                                    @flag,
                                    @msg)";

        $stmt->dpPrepare($query);

        //$stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag, @msg as msg";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        //$flag = $stmt->dpFetchAll();
        //return ($flag)? intval($flag[0]['flag']) : -1;
        return $stmt->dpFetchAll(); //retorna array bidimensional con flag y msg
    }




    public static function exportTxt($id_contrato, $periodo) { //ok
        $stmt=new sQuery();
        //se agrega un select de nivel superior para porder ordenarlo y que salga en el mismo orden que el pdf
        $query = "select * from
(select em.legajo, nccc.codigo, sum(npec.cantidad) as cantidad, nccc.variable, em.id_convenio
from nov_partes np
join nov_parte_empleado npe on npe.id_parte = np.id_parte
join empleados em on em.id_empleado = npe.id_empleado
join nov_parte_empleado_concepto npec on npec.id_parte_empleado = npe.id_parte_empleado
join nov_concepto_convenio_contrato nccc on nccc.id_concepto_convenio_contrato = npec.id_concepto_convenio_contrato
join nov_periodos per on np.id_periodo = per.id_periodo
where np.id_contrato in ($id_contrato)
and np.last_calc_status is not null
and nccc.id_concepto not in (41)
and per.periodo = :periodo
group by npe.id_empleado, nccc.codigo, nccc.variable
UNION

select legajo, codigo, sum(cantidad) as cantidad, variable, id_convenio
from
(select em.legajo, nccc.codigo,
sum(ifnull(ns.cantidad1,0)) as cantidad,
nccc.variable, em.id_convenio
from nov_sucesos ns
join empleados em on em.id_empleado = ns.id_empleado
join nov_periodos per1 on per1.id_periodo = ns.id_periodo1
join nov_eventos_l nel on nel.id_evento = ns.id_evento
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = nel.id_concepto
													and nccc.id_contrato = per1.id_contrato
													and nccc.id_convenio = em.id_convenio
                                                    and nccc.id_concepto in (15, 16, 18, 29))
where per1.periodo = :periodo
and per1.id_contrato in ($id_contrato)
group by em.id_empleado, nccc.codigo, nccc.variable
UNION ALL
select em.legajo, nccc.codigo,
sum(ifnull(ns.cantidad2,0)) as cantidad,
nccc.variable, em.id_convenio
from nov_sucesos ns
join empleados em on em.id_empleado = ns.id_empleado
join nov_periodos per2 on per2.id_periodo = ns.id_periodo2
join nov_eventos_l nel on nel.id_evento = ns.id_evento
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = nel.id_concepto
													and nccc.id_contrato = per2.id_contrato
													and nccc.id_convenio = em.id_convenio
                                                    and nccc.id_concepto in (15, 16, 18, 29))
where per2.periodo = :periodo
and per2.id_contrato in ($id_contrato)
group by em.id_empleado, nccc.codigo, nccc.variable) as temp
group by legajo, codigo, variable

UNION
select em.legajo, nccc.codigo,
func_nov_horas('DH', 'CAL', group_concat(ec.id_contrato), em.id_empleado, :periodo) as cantidad,
nccc.variable, em.id_convenio
from empleado_contrato ec
join empleados em on em.id_empleado = ec.id_empleado
join nov_periodos p on (p.periodo = :periodo and p.id_contrato = ec.id_contrato)
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = 40 and nccc.id_convenio = em.id_convenio and nccc.id_contrato = ec.id_contrato)
where ec.id_contrato in ($id_contrato)
and ec.fecha_desde <= p.fecha_hasta
and (ec.fecha_hasta is null or ec.fecha_hasta >= p.fecha_desde)
group by em.id_empleado, nccc.codigo
having cantidad > 0
/*UNION
select em.legajo, nccc.codigo,
func_nov_horas('DHNT', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo) as cantidad,
nccc.variable, em.id_convenio
from empleado_contrato ec
join empleados em on em.id_empleado = ec.id_empleado
join nov_periodos p on (p.periodo = :periodo and p.id_contrato = ec.id_contrato)
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = 36 and nccc.id_convenio = em.id_convenio and nccc.id_contrato = ec.id_contrato)
where ec.id_contrato in ($id_contrato)
and ec.fecha_desde <= p.fecha_hasta
and (ec.fecha_hasta is null or ec.fecha_hasta >= p.fecha_desde)
group by em.id_empleado, nccc.codigo
having cantidad > 0*/
/*UNION
select em.legajo, nccc.codigo,
func_nov_horas('DCNT223', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo) as cantidad,
nccc.variable, em.id_convenio
from empleado_contrato ec
join empleados em on em.id_empleado = ec.id_empleado
join nov_periodos p on (p.periodo = :periodo and p.id_contrato = ec.id_contrato)
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = 37 and nccc.id_convenio = em.id_convenio and nccc.id_contrato = ec.id_contrato)
where ec.id_contrato in ($id_contrato)
and ec.fecha_desde <= p.fecha_hasta
and (ec.fecha_hasta is null or ec.fecha_hasta >= p.fecha_desde)
group by em.id_empleado, nccc.codigo
having cantidad > 0*/
UNION
select em.legajo, nccc.codigo,
func_nov_horas('DCNT', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo) as cantidad,
nccc.variable, em.id_convenio
from empleado_contrato ec
join empleados em on em.id_empleado = ec.id_empleado
join nov_periodos p on (p.periodo = :periodo and p.id_contrato = ec.id_contrato)
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = 38 and nccc.id_convenio = em.id_convenio and nccc.id_contrato = ec.id_contrato)
where ec.id_contrato in ($id_contrato)
and ec.fecha_desde <= p.fecha_hasta
and (ec.fecha_hasta is null or ec.fecha_hasta >= p.fecha_desde)
group by em.id_empleado, nccc.codigo
having cantidad > 0
/*UNION
select em.legajo, '5999',
func_nov_horas('DCNT223', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo) as cantidad,
nccc.variable, em.id_convenio
from empleado_contrato ec
join empleados em on em.id_empleado = ec.id_empleado
join nov_periodos p on (p.periodo = :periodo and p.id_contrato = ec.id_contrato)
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = 37 and nccc.id_convenio = em.id_convenio and nccc.id_contrato = ec.id_contrato)
where ec.id_contrato in ($id_contrato)
and ec.fecha_desde <= p.fecha_hasta
and (ec.fecha_hasta is null or ec.fecha_hasta >= p.fecha_desde)
group by em.id_empleado, nccc.codigo
having cantidad > 0*/
UNION
select em.legajo, '9501',
func_nov_horas('DHDD', 'CTO', group_concat(ec.id_contrato), em.id_empleado, :periodo) as cantidad,
nccc.variable, em.id_convenio
from empleado_contrato ec
join empleados em on em.id_empleado = ec.id_empleado
join nov_periodos p on (p.periodo = :periodo and p.id_contrato = ec.id_contrato)
join nov_concepto_convenio_contrato nccc on (nccc.id_concepto = 39 and nccc.id_convenio = em.id_convenio and nccc.id_contrato = ec.id_contrato)
where ec.id_contrato in ($id_contrato)
and ec.fecha_desde <= p.fecha_hasta
and (ec.fecha_hasta is null or ec.fecha_hasta >= p.fecha_desde)
group by em.id_empleado, nccc.codigo
having cantidad > 0
) as temp
order by id_convenio asc, legajo asc, codigo asc";

        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':periodo', $periodo);

        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public function updateComentarios(){
        $stmt=new sQuery();
        $query="update nov_partes set comentarios =:comentarios
                where id_parte =:id_parte";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteParte(){ //ok
        $stmt=new sQuery();
        $query="delete
                from nov_partes where id_parte = :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdParte());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public static function getEmpleados($fecha, $id_contrato) { //ok
        //trae los empleados activos de un contrato, para la fecha indicada, tambien el nro de parte.
        $stmt=new sQuery();
        /*$query="select em.id_empleado, em.legajo, em.apellido, em.nombre, np.id_parte, npe.id_parte_empleado, np.last_calc_status, cu.nombre_corto,
                      (select count(*) from nov_parte_orden npox where npox.id_parte = np.id_parte) as orden_count,
                      (select count(*) from nov_parte_empleado_concepto npecx join nov_parte_empleado npex on npex.id_parte_empleado = npecx.id_parte_empleado where npex.id_parte = np.id_parte and npex.id_empleado = em.id_empleado) as concept_count
                      from v_sec_empleados em
                      join empleado_contrato ec on (ec.id_empleado = em.id_empleado and (ec.fecha_hasta is null or ec.fecha_hasta > sysdate()))
					  left join nov_parte_empleado npe join nov_partes np on np.id_parte = npe.id_parte on
								(
                                np.id_contrato = :id_contrato
                                and np.fecha_parte = STR_TO_DATE(:fecha, '%d/%m/%Y')
                                and npe.id_empleado = em.id_empleado
                                )
                      left join nov_cuadrillas cu on np.id_cuadrilla = cu.id_cuadrilla
                      where em.fecha_baja is null
                      and em.fecha_alta <= STR_TO_DATE(:fecha, '%d/%m/%Y')
                      and ec.id_contrato = :id_contrato
                      order by isnull(np.id_parte) desc, cu.nombre_corto asc, npe.conductor desc, em.apellido, em.nombre";*/
        $query="select em.id_empleado, em.legajo, em.apellido, em.nombre, np.id_parte, npe.id_parte_empleado, np.last_calc_status, cu.nombre_corto,
                      (select count(*) from nov_parte_orden npox where npox.id_parte = np.id_parte) as orden_count,
                      (select count(*) from nov_parte_empleado_concepto npecx join nov_parte_empleado npex on npex.id_parte_empleado = npecx.id_parte_empleado where npex.id_parte = np.id_parte and npex.id_empleado = em.id_empleado) as concept_count,
                      (select count(*) from nov_partes npx join nov_parte_empleado npex on npx.id_parte = npex.id_parte where npex.id_empleado = em.id_empleado and npx.fecha_parte = STR_TO_DATE(:fecha, '%d/%m/%Y') and npx.id_contrato != :id_contrato) as parte_count
                      from v_sec_empleados em
                      join empleado_contrato ec on (ec.id_empleado = em.id_empleado and (ec.fecha_hasta is null or ec.fecha_hasta >= STR_TO_DATE(:fecha, '%d/%m/%Y')) and ec.fecha_desde <= STR_TO_DATE(:fecha, '%d/%m/%Y'))
					  left join nov_parte_empleado npe join nov_partes np on np.id_parte = npe.id_parte on
								(
                                np.id_contrato = :id_contrato
                                and np.fecha_parte = STR_TO_DATE(:fecha, '%d/%m/%Y')
                                and npe.id_empleado = em.id_empleado
                                )
                      left join nov_cuadrillas cu on np.id_cuadrilla = cu.id_cuadrilla
                      where ec.id_contrato = :id_contrato
                      order by isnull(np.id_parte) desc, cu.nombre_corto asc, npe.conductor desc, em.apellido, em.nombre";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha', $fecha);
        //$stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getParteAnterior($id_empleado, $fecha_parte, $id_contrato) {
        //trae el ultimo parte del empleado
        $stmt=new sQuery();
        $query="select *
from nov_parte_empleado npe
join nov_partes np on np.id_parte = npe.id_parte
where npe.id_empleado = :id_empleado
and np.fecha_parte < STR_TO_DATE(:fecha_parte, '%d/%m/%Y')
and np.id_contrato = :id_contrato
order by np.fecha_parte desc
limit 1";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':fecha_parte', $fecha_parte);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public static function getPdf($fecha_desde, $fecha_hasta, $id_contrato, $cuadrilla) {
        $stmt=new sQuery();
        /*$query="select pa.id_parte,
                    (select count(*) from nov_parte_orden npox where npox.id_parte = pa.id_parte) as orden_count,
                    (select count(*) from nov_parte_empleado_concepto npecx join nov_parte_empleado npex on npex.id_parte_empleado = npecx.id_parte_empleado where npex.id_parte = pa.id_parte) as concept_count,
                    DATE_FORMAT(pa.created_date,  '%d/%m/%Y') as created_date,
                    DATE_FORMAT(pa.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    pa.cuadrilla, pa.id_area, pa.id_vehiculo, pa.id_evento, pa.id_contrato, pa.last_calc_status,
                    concat(ar.codigo, ' ', ar.nombre) as area,
                    ve.nro_movil as vehiculo,
                    concat(nec.codigo, ' ', nec.nombre) as evento,
                    co.nombre as contrato,
                    us.user, pa.created_by,
                    pa.id_periodo, pe.closed_date
                    from nov_partes pa
                    left join nov_areas ar on pa.id_area = ar.id_area
                    left join vto_vehiculos ve on pa.id_vehiculo = ve.id_vehiculo
                    left join nov_eventos_c nec on pa.id_evento = nec.id_evento
                    join v_sec_contratos_control co on pa.id_contrato = co.id_contrato
                    join sec_users us on pa.created_by = us.id_user
                    join nov_periodos pe on pe.id_periodo = pa.id_periodo
                    and pa.fecha_parte between if(:fecha_desde is null, pa.fecha_parte, :fecha_desde)
                    and if(:fecha_hasta is null, pa.fecha_parte, :fecha_hasta)
                    and pa.id_contrato =  ifnull(:id_contrato, pa.id_contrato)
                    and pa.id_periodo =  ifnull(:id_periodo, pa.id_periodo)
                    and pa.cuadrilla =  ifnull(:cuadrilla, pa.cuadrilla)
                    order by pa.fecha_parte asc";*/
        $query = "select np.id_parte, np.comentarios,
DATE_FORMAT(np.fecha_parte,  '%d/%m/%Y') as fecha_parte,
cu.nombre_corto_op as cuadrilla, na.nombre as area, nec.nombre as evento, npo.nro_parte_diario, npo.orden_tipo, npo.orden_nro
from nov_partes np
join nov_cuadrillas cu on np.id_cuadrilla = cu.id_cuadrilla
left join nov_parte_orden npo on npo.id_parte = np.id_parte
left join nov_eventos_c nec on nec.id_evento = np.id_evento
left join nov_areas na on na.id_area = np.id_area
where np.id_contrato = :id_contrato
and np.fecha_parte between :fecha_desde and :fecha_hasta
and np.cuadrilla = ifnull(:cuadrilla, np.cuadrilla)
order by cu.nombre_corto_op asc, np.fecha_parte asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':cuadrilla', $cuadrilla);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>