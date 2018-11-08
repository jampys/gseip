<?php

class GrupoVehiculo
{
    private $id_grupo;
    private $nombre;
    private $id_vencimiento;
    private $nro_referencia;
    private $fecha_baja;

    // GETTERS
    function getIdGrupo()
    { return $this->id_grupo;}

    function getNombre()
    { return $this->nombre;}

    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getNroReferencia()
    { return $this->nro_referencia;}

    function getFechaBaja()
    { return $this->fecha_baja;}


    //SETTERS
    function setIdGrupo($val)
    {  $this->id_grupo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setIdVencimiento($val)
    { $this->id_vencimiento=$val;}

    function setNroReferencia($val)
    { $this->nro_referencia=$val;}

    function setFechaBaja($val)
    { $this->fecha_baja=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_grupo, nombre, id_vencimiento,
                      nro_referencia,
                      DATE_FORMAT(fecha_baja,'%d/%m/%Y') as fecha_baja
                      from vto_grupos_v
                      where id_grupo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdGrupo($rows[0]['id_grupo']);
            $this->setNombre($rows[0]['nombre']);
            $this->setIdVencimiento($rows[0]['id_vencimiento']);
            $this->setNroReferencia($rows[0]['nro_referencia']);
            $this->setFechaBaja($rows[0]['fecha_baja']);
        }
    }


    public static function getGrupos() { //ok
        $stmt=new sQuery();
        $query="select gru.id_grupo, gru.nombre, gru.id_vencimiento, gru.nro_referencia,
                DATE_FORMAT(gru.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                vto.nombre as vencimiento
                from vto_grupos_v gru
                join vto_vencimiento_v vto on gru.id_vencimiento = vto.id_vencimiento
                order by nombre asc";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_grupo)
        {$rta = $this->updateGrupo();}
        else
        {$rta =$this->insertGrupo();}
        return $rta;
    }


    public function updateGrupo(){ //ok
        $stmt=new sQuery();
        $query="update vto_grupos_v set nombre =:nombre,
                      id_vencimiento = :id_vencimiento,
                      nro_referencia = :nro_referencia,
                      fecha_baja = STR_TO_DATE(:fecha_baja, '%d/%m/%Y')
                where id_grupo =:id_grupo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':nro_referencia', $this->getNroReferencia());
        $stmt->dpBind(':fecha_baja', $this->getFechaBaja());
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertGrupo(){ //ok
        $stmt=new sQuery();
        $query="insert into vto_grupos_v(nombre, id_vencimiento, nro_referencia, fecha_baja)
                values(:nombre, :id_vencimiento, :nro_referencia, STR_TO_DATE(:fecha_baja, '%d/%m/%Y'))";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':nro_referencia', $this->getNroReferencia());
        $stmt->dpBind(':fecha_baja', $this->getFechaBaja());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteGrupo(){ //ok
        $stmt=new sQuery();
        $query="delete from vto_grupos_v where id_grupo =:id_grupo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }









}




?>