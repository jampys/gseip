<?php

class Accion
{
    private $id_accion;
    private $id_no_conformidad;
    private $accion; //descripcion de la accion
    private $id_responsable_ejecucion;
    private $fecha_implementacion;
    private $id_user;
    private $created_date;

    // GETTERS
    function getIdAccion()
    { return $this->id_accion;}

    function getIdNoConformidad()
    { return $this->id_no_conformidad;}

    function getAccion()
    { return $this->accion;}

    function getIdResponsableEjecucion()
    { return $this->id_responsable_ejecucion;}

    function getFechaImplementacion()
    { return $this->fecha_implementacion;}

    function getIdUser()
    { return $this->id_user;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdAccion($val)
    { $this->id_accion=$val;}

    function setIdNoConformidad($val)
    {  $this->id_no_conformidad=$val;}

    function setAccion($val)
    {  $this->accion=$val;}

    function setIdResponsableEjecucion($val)
    { $this->id_responsable_ejecucion=$val;}

    function setFechaImplementacion($val)
    { $this->fecha_implementacion=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_accion, id_no_conformidad, accion, id_responsable_ejecucion,
                      DATE_FORMAT(fecha_implementacion, '%d/%m/%Y') as fecha_implementacion,
                      id_user,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from nc_accion
                      where id_accion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdAccion($rows[0]['id_accion']);
            $this->setIdNoConformidad($rows[0]['id_no_conformidad']);
            $this->setAccion($rows[0]['accion']);
            $this->setIdResponsableEjecucion($rows[0]['id_responsable_ejecucion']);
            $this->setFechaImplementacion($rows[0]['fecha_implementacion']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getAcciones($id_no_conformidad) { //ok
        $stmt=new sQuery();
        $query = "select ac.id_accion, ac.id_no_conformidad, ac.accion, ac.id_responsable_ejecucion,
                  DATE_FORMAT(ac.fecha_implementacion, '%d/%m/%Y') as fecha_implementacion,
                  DATE_FORMAT(ac.created_date, '%d/%m/%y') as created_date,
                  ac.id_user,
                  us.user
                  from nc_accion ac
                  join sec_users us on ac.id_user = us.id_user
                  where ac.id_no_conformidad = :id_postulacion
                  order by ac.fecha_implementacion asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_no_conformidad', $id_no_conformidad);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){
        if($this->id_etapa)
        {$rta = $this->updateEtapa();}
        else
        {$rta =$this->insertEtapa();}
        return $rta;
    }


    public function updateEtapa(){
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

    private function insertEtapa(){
        $stmt=new sQuery();
        $query="insert into sel_etapas(id_postulacion, fecha, fecha_etapa, etapa, aplica, motivo , modo_contacto, comentarios, id_user)
                values(:id_postulacion, sysdate(), STR_TO_DATE(:fecha_etapa, '%d/%m/%Y'), :etapa, :aplica, :motivo, :modo_contacto, :comentarios, :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulacion', $this->getIdPostulacion());
        $stmt->dpBind(':fecha_etapa', $this->getFechaEtapa());
        $stmt->dpBind(':etapa', $this->getEtapa());
        $stmt->dpBind(':aplica', $this->getAplica());
        $stmt->dpBind(':motivo', $this->getMotivo());
        $stmt->dpBind(':modo_contacto', $this->getModoContacto());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_user', $this->getIdUser());
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