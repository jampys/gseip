<?php


class ParteEmpleadoConcepto
{
    private $id_parte_empleado_concepto;
    private $id_parte_empleado;
    private $id_concepto_convenio_contrato;
    private $cantidad;


    // GETTERS
    function getIdParteEmpleadoConcepto()
    { return $this->id_parte_empleado_concepto;}

    function getIdParteEmpleado()
    { return $this->id_parte_empleado;}

    function getIdConceptoConvenioContrato()
    { return $this->id_concepto_convenio_contrato;}

    function getCantidad()
    { return $this->cantidad;}


    //SETTERS
    function setIdParteEmpleadoConcepto($val)
    { $this->id_parte_empleado_concepto=$val;}

    function setIdParteEmpleado($val)
    { $this->id_parte_empleado=$val;}

    function setIdConceptoConvenioContrato($val)
    { $this->id_concepto_convenio_contrato=$val;}

    function setCantidad($val)
    {  $this->cantidad=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_parte_empleado_concepto,
                    id_parte_empleado, id_concepto_convenio_contrato
                    from nov_parte_empleado_concepto where id_parte_empleado_concepto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParteEmpleadoConcepto($rows[0]['id_parte_empleado_concepto']);
            $this->setIdParteEmpleado($rows[0]['id_parte_empleado']);
            $this->setIdConceptoConvenioContrato($rows[0]['id_concepto_convenio_contrato']);
            $this->setCantidad($rows[0]['cantidad']);
        }
    }


    public static function getParteEmpleadoConcepto($fecha_desde, $fecha_hasta, $id_contrato, $d) { //ok
        $stmt=new sQuery();
        $query="select pa.id_parte,
                    DATE_FORMAT(pa.fecha,  '%d/%m/%Y') as fecha,
                    DATE_FORMAT(pa.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    pa.cuadrilla, pa.id_area, pa.id_vehiculo, pa.id_evento, pa.id_contrato, pa.calculado,
                    concat(ar.codigo, ' ', ar.nombre) as area,
                    concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                    concat(nec.codigo, ' ', nec.nombre) as evento,
                    co.nombre as contrato,
                    us.user
                    from nov_partes pa
                    left join nov_areas ar on pa.id_area = ar.id_area
                    left join vto_vehiculos ve on pa.id_vehiculo = ve.id_vehiculo
                    left join nov_eventos_c nec on pa.id_evento = nec.id_evento
                    left join contratos co on pa.id_contrato = co.id_contrato
                    join sec_users us on pa.id_user = us.id_user
                    and pa.fecha_parte between if(:fecha_desde is null, pa.fecha_parte, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                    and if(:fecha_hasta is null, pa.fecha_parte, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))
                    and pa.id_contrato =  ifnull(:id_contrato, pa.id_contrato)
                    order by pa.fecha_parte asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){
        if($this->id_parte)
        {$rta = $this->updateParte();}
        else
        {$rta =$this->insertParte();}
        return $rta;
    }


    public function updateParte(){
        $stmt=new sQuery();
        $query = 'CALL sp_calcularNovedades(:id_parte,
                                        :id_area,
                                        :id_vehiculo,
                                        :id_evento,
                                        :hs_normal,
                                        :hs_50,
                                        :hs_100,
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

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag, @msg as msg";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        //$flag = $stmt->dpFetchAll();
        //return ($flag)? intval($flag[0]['flag']) : -1;
        return $stmt->dpFetchAll(); //retorna array bidimensional con flag y msg
    }


    public function insertParte(){

        $stmt=new sQuery();
        $query="insert into nov_partes(fecha, fecha_parte, cuadrilla, id_area, id_vehiculo, id_evento, id_contrato, id_user)
                values(sysdate(), STR_TO_DATE(:fecha_parte, '%d/%m/%Y'), :cuadrilla, :id_area, :id_vehiculo, :id_evento, :id_contrato, :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_parte', $this->getFechaParte());
        $stmt->dpBind(':cuadrilla', $this->getCuadrilla());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deletePuesto(){
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function autocompletarPuestos($term) {
        $stmt=new sQuery();
        $query = "select *
                  from puestos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

}



?>