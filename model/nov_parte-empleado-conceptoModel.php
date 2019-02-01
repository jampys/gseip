<?php


class ParteEmpleadoConcepto
{
    private $id_parte_empleado_concepto;
    private $id_parte_empleado;
    private $id_concepto_convenio_contrato;
    private $cantidad;

    private $created_by;
    private $created_date;
    private $tipo_calculo;


    // GETTERS
    function getIdParteEmpleadoConcepto()
    { return $this->id_parte_empleado_concepto;}

    function getIdParteEmpleado()
    { return $this->id_parte_empleado;}

    function getIdConceptoConvenioContrato()
    { return $this->id_concepto_convenio_contrato;}

    function getCantidad()
    { return $this->cantidad;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}

    function getTipoCalculo()
    { return $this->tipo_calculo;}


    //SETTERS
    function setIdParteEmpleadoConcepto($val)
    { $this->id_parte_empleado_concepto=$val;}

    function setIdParteEmpleado($val)
    { $this->id_parte_empleado=$val;}

    function setIdConceptoConvenioContrato($val)
    { $this->id_concepto_convenio_contrato=$val;}

    function setCantidad($val)
    {  $this->cantidad=$val;}

    function setCreatedBy($val)
    {  $this->created_by=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}

    function setTipoCalculo($val)
    {  $this->tipo_calculo=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_parte_empleado_concepto,
                    id_parte_empleado, id_concepto_convenio_contrato,
                    tipo_calculo
                    from nov_parte_empleado_concepto where id_parte_empleado_concepto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParteEmpleadoConcepto($rows[0]['id_parte_empleado_concepto']);
            $this->setIdParteEmpleado($rows[0]['id_parte_empleado']);
            $this->setIdConceptoConvenioContrato($rows[0]['id_concepto_convenio_contrato']);
            $this->setCantidad($rows[0]['cantidad']);
            $this->setTipoCalculo($rows[0]['tipo_calculo']);
        }
    }


    //public static function getParteEmpleadoConcepto($fecha_desde, $fecha_hasta, $id_contrato, $d) { //ok
    public static function getParteEmpleadoConcepto($id_parte) { //ok
        $stmt=new sQuery();
        $query="select npe.id_parte, npe.id_parte_empleado, npe.id_empleado,
npec.id_parte_empleado_concepto, npec.id_concepto_convenio_contrato,
npec.cantidad,
nccto.nombre as concepto,
ncnio.codigo as convenio,
nccc.codigo,
nccc.variable,
em.legajo
from nov_parte_empleado_concepto npec
join nov_parte_empleado npe on npe.id_parte_empleado = npec.id_parte_empleado
join empleados em on npe.id_empleado = em.id_empleado
join nov_concepto_convenio_contrato nccc on nccc.id_concepto_convenio_contrato = npec.id_concepto_convenio_contrato
join nov_conceptos nccto on nccto.id_concepto = nccc.id_concepto
join nov_convenios ncnio on ncnio.id_convenio = nccc.id_convenio
where npe.id_parte = :id_parte
order by npe.id_empleado asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $id_parte);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){
        if($this->id_parte_empleado_concepto)
        {$rta = $this->updateParteEmpleadoConcepto();}
        else
        {$rta =$this->insertParteEmpleadoConcepto();}
        return $rta;
    }


    public function updateParteEmpleadoConcepto(){
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


    public function insertParteEmpleadoConcepto(){ //ok

        $stmt=new sQuery();
        $query="insert into nov_parte_empleado_concepto(id_parte_empleado, id_concepto_convenio_contrato, cantidad, created_by, created_date, tipo_calculo, motivo)
                values(:id_parte_empleado, :id_concepto_convenio_contrato, :cantidad, :created_by, sysdate(), :tipo_calculo, :motivo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte_empleado', $this->getIdParteEmpleado());
        $stmt->dpBind(':id_concepto_convenio_contrato', $this->getIdConceptoConvenioContrato());
        $stmt->dpBind(':cantidad', $this->getCantidad());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpBind(':tipo_calculo', 'M');
        $stmt->dpBind(':motivo', 'motivo');
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



}



?>