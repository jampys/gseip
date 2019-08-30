<?php

class Habilita
{
    private $id;
    private $ot;
    private $habilita;
    private $cantidad;
    private $unitario;
    private $importe;
    private $created_by;
    private $created_date;

    // GETTERS
    function getId()
    { return $this->id;}

    function getOt()
    { return $this->ot;}

    function getHabilita()
    { return $this->habilita;}

    function getCantidad()
    { return $this->cantidad;}

    function getUnitario()
    { return $this->unitario;}

    function getImporte()
    { return $this->importe;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setId($val)
    { $this->id=$val;}

    function setOt($val)
    {  $this->ot=$val;}

    function setHabilita($val)
    { $this->habilita=$val;}

    function setCantidad($val)
    { $this->cantidad=$val;}

    function setUnitario($val)
    { $this->unitario=$val;}

    function setImporte($val)
    { $this->importe=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}



    function __construct($nro=0){ //constructor /ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id, ot, habilita, cantidad, unitario, importe, created_by,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from nov_habilitas
                      where id = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setId($rows[0]['id']);
            $this->setOt($rows[0]['ot']);
            $this->setHabilita($rows[0]['habilita']);
            $this->setCantidad($rows[0]['cantidad']);
            $this->setUnitario($rows[0]['unitario']);
            $this->setImporte($rows[0]['importe']);
            $this->setCreatedBy($rows[0]['created_by']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getEtapas($id_postulacion) {
        $stmt=new sQuery();
        $query = "select et.id_etapa, et.id_postulacion,
                  DATE_FORMAT(et.fecha, '%d/%m/%Y') as fecha,
                  DATE_FORMAT(et.fecha_etapa, '%d/%m/%y') as fecha_etapa,
                  et.etapa, et.aplica, et.motivo, et.modo_contacto, et.comentarios, et.id_user,
                  us.user
                  from sel_etapas et
                  join sec_users us on et.id_user = us.id_user
                  where et.id_postulacion = :id_postulacion
                  order by et.fecha_etapa asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulacion', $id_postulacion);
        //$stmt->dpBind(':id_grupo', $id_grupo);
        //$stmt->dpBind(':id_vencimiento', $id_vencimiento);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':renovado', $renovado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id)
        {$rta = $this->updateHabilita();}
        else
        {$rta =$this->insertHabilita();}
        return $rta;
    }


    public function updateHabilita(){
        $stmt=new sQuery();
        $query="update sel_etapas set fecha_etapa = STR_TO_DATE(:fecha_etapa, '%d/%m/%Y'),
                etapa = :etapa,
                aplica = :aplica,
                motivo = :motivo,
                modo_contacto = :modo_contacto,
                comentarios = :comentarios
                where id_etapa = :id_etapa";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_etapa', $this->getFechaEtapa());
        $stmt->dpBind(':etapa', $this->getEtapa());
        $stmt->dpBind(':aplica', $this->getAplica());
        $stmt->dpBind(':motivo', $this->getMotivo());
        $stmt->dpBind(':modo_contacto', $this->getModoContacto());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_etapa', $this->getIdEtapa());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertHabilita(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_habilitas(ot, habilita, cantidad, unitario, importe, created_by , created_date)
                values(:ot, :habilita, :cantidad, :unitario, :importe, :created_by, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':ot', $this->getOt());
        $stmt->dpBind(':habilita', $this->getHabilita());
        $stmt->dpBind(':cantidad', $this->getCantidad());
        $stmt->dpBind(':unitario', $this->getUnitario());
        $stmt->dpBind(':importe', $this->getImporte());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteEtapa(){
        $stmt=new sQuery();
        $query="delete from sel_etapas where id_etapa = :id_etapa";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_etapa', $this->getIdEtapa());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }





}




?>