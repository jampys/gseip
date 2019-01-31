<?php


class ConceptoConvenioContrato
{
    private $id_concepto_convenio_contrato;
    private $id_concepto;
    private $id_contrato;
    private $id_convenio;
    private $codigo;
    private $variable;
    private $descripcion;
    private $default_value;


    // GETTERS
    function getIdConceptoConvenioContrato()
    { return $this->id_concepto_convenio_contrato;}

    function getIdConcepto()
    { return $this->id_concepto;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getIdConvenio()
    { return $this->id_convenio;}

    function getCodigo()
    { return $this->codigo;}

    function getVariable()
    { return $this->variable;}

    function getDescripcion()
    { return $this->descripcion;}

    function getDefaultValue()
    { return $this->default_value;}


    //SETTERS
    function setIdConceptoConvenioContrato($val)
    { $this->id_concepto_convenio_contrato=$val;}

    function setIdConcepto($val)
    { $this->id_concepto=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setIdConvenio($val)
    {  $this->id_convenio=$val;}

    function setCodigo($val)
    {  $this->codigo=$val;}

    function setVariable($val)
    {  $this->variable=$val;}

    function setDescripcion($val)
    {  $this->descripcion=$val;}

    function setDefaultValue($val)
    {  $this->default_value=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_concepto_convenio_contrato,
                    id_concepto, id_contrato, id_convenio,
                    codigo, variable, descripcion, default_value
                    from nov_concepto_convenio_contrato nccc
                    where nccc.id_concepto_convenio_contrato = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdConceptoConvenioContrato($rows[0]['id_concepto_convenio_contrato']);
            $this->setIdConcepto($rows[0]['id_concepto']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setIdConvenio($rows[0]['id_convenio']);
            $this->setCodigo($rows[0]['codigo']);
            $this->setVariable($rows[0]['variable']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setDefaultValue($rows[0]['default_value']);
        }
    }



    public static function getParteEmpleadoConcepto($id_contrato, $id_convenio) {
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
        //$stmt->dpBind(':fecha_hasta', $fecha_hasta);
        //$stmt->dpBind(':id_contrato', $id_contrato);
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



}



?>