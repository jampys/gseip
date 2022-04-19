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



    public static function getConceptoConvenioContrato($id_contrato, $id_convenio) { //ok
        //obtengo los conceptos para un determinado contrato y convenio (select dependiente)
        $stmt=new sQuery();
        $query="select nccc.id_concepto_convenio_contrato, nccc.id_concepto, nccc.id_contrato, nccc.id_convenio,
nccc.codigo, nccc.variable, nccc.descripcion, nccc.default_value,
nctos.nombre as concepto,
ncnios.codigo as convenio
from nov_concepto_convenio_contrato nccc
join nov_conceptos nctos on nctos.id_concepto = nccc.id_concepto
join nov_convenios ncnios on ncnios.id_convenio = nccc.id_convenio
where nccc.id_contrato = :id_contrato
and nccc.id_convenio = ifnull(:id_convenio, nccc.id_convenio)
and nccc.enabled = 1
order by nctos.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_convenio', $id_convenio);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }





}



?>