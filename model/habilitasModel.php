<?php

class Habilita
{
    private $id;
    private $posicion;
    private $ot;
    private $habilita;
    private $cantidad;
    private $unitario;
    private $importe;
    private $centro;
    private $certificado;
    private $fecha;

    private $created_by;
    private $created_date;



    // GETTERS
    function getId()
    { return $this->id;}

    function getPosicion()
    { return $this->posicion;}

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

    function getCentro()
    { return $this->centro;}

    function getCertificado()
    { return $this->certificado;}

    function getFecha()
    { return $this->fecha;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setId($val)
    { $this->id=$val;}

    function setPosicion($val)
    { $this->posicion=$val;}

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

    function setCentro($val)
    { $this->centro=$val;}

    function setCertificado($val)
    { $this->certificado=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}



    function __construct($nro=0){ //constructor /ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id, posicion, ot, habilita, cantidad, unitario, importe,
                      centro, certificado, fecha,
                      created_by,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from nov_habilitas
                      where id = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setId($rows[0]['id']);
            $this->setPosicion($rows[0]['posicion']);
            $this->setOt($rows[0]['ot']);
            $this->setHabilita($rows[0]['habilita']);
            $this->setCantidad($rows[0]['cantidad']);
            $this->setUnitario($rows[0]['unitario']);
            $this->setImporte($rows[0]['importe']);
            $this->setCentro($rows[0]['centro']);
            $this->setCertificado($rows[0]['certificado']);
            $this->setFecha($rows[0]['fecha']);
            $this->setCreatedBy($rows[0]['created_by']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public function getHabilitas() {
        $stmt=new sQuery();
        $query = "select nh.id, nh.habilita, nh.ot, nh.cantidad, nh.unitario, nh.importe, nh.centro, nh.certificado, nh.fecha,
np.id_parte,
DATE_FORMAT(np.fecha_parte,  '%d/%m/%Y') as fecha_parte,
np.cuadrilla,
per.nombre as periodo,
na.nombre as area
from nov_habilitas nh
left join nov_parte_orden npo on npo.orden_nro = nh.ot
left join nov_partes np on np.id_parte = npo.id_parte
left join nov_periodos per on per.id_periodo = np.id_periodo
left join nov_areas na on na.id_area = np.id_area
where nh.ot like :ot
and nh.habilita like :habilita
and nh.certificado like :certificado
group by nh.id
order by nh.habilita asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':ot', "%".$this->getOt()."%"); //2006589385
        $stmt->dpBind(':habilita', "%".$this->getHabilita()."%"); //4503848515
        $stmt->dpBind(':certificado', "%".$this->getCertificado()."%"); //0000223503
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
        $query="insert into nov_habilitas(posicion, ot, habilita, cantidad, unitario, importe, centro, certificado, fecha, created_by , created_date)
                values(:posicion, :ot, :habilita, :cantidad, :unitario, :importe, :centro, :certificado, STR_TO_DATE(:fecha, '%d.%m.%Y'), :created_by, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':posicion', $this->getPosicion());
        $stmt->dpBind(':ot', $this->getOt());
        $stmt->dpBind(':habilita', $this->getHabilita());
        $stmt->dpBind(':cantidad', $this->getCantidad());
        $stmt->dpBind(':unitario', $this->getUnitario());
        $stmt->dpBind(':importe', $this->getImporte());
        $stmt->dpBind(':centro', $this->getCentro());
        $stmt->dpBind(':certificado', $this->getCertificado());
        $stmt->dpBind(':fecha', $this->getFecha());
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