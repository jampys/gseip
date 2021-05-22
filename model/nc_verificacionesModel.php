<?php

class Verificacion
{
    private $id_verificacion;
    private $id_no_conformidad;
    private $verificacion_eficacia;
    private $fecha_verificacion;
    private $id_user;
    private $created_date;

    // GETTERS
    function getIdVerificacion()
    { return $this->id_verificacion;}

    function getIdNoConformidad()
    { return $this->id_no_conformidad;}

    function getVerificacionEficacia()
    { return $this->verificacion_eficacia;}

    function getFechaVerificacion()
    { return $this->fecha_verificacion;}

    function getIdUser()
    { return $this->id_user;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdVerificacion($val)
    { $this->id_verificacion=$val;}

    function setIdNoConformidad($val)
    {  $this->id_no_conformidad=$val;}

    function setVerificacionEficacia($val)
    {  $this->verificacion_eficacia=$val;}

    function setFechaVerificacion($val)
    {  $this->fecha_verificacion=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_verificacion, id_no_conformidad, verificacion_eficacia,
                      DATE_FORMAT(fecha_verificacion, '%d/%m/%Y') as fecha_verificacion,
                      id_user,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from nc_accion
                      where id_accion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdVerificacion($rows[0]['id_verificacion']);
            $this->setIdNoConformidad($rows[0]['id_no_conformidad']);
            $this->setVerificacionEficacia($rows[0]['verificacion_eficacia']);
            $this->setFechaVerificacion($rows[0]['fecha_verificacion']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getVerificaciones($id_no_conformidad) { //ok
        $stmt=new sQuery();
        $query = "select ve.id_verificacion, ve.id_no_conformidad, ve.verificacion_eficacia,
                  DATE_FORMAT(ve.fecha_verificacion, '%d/%m/%Y') as fecha_verificacion,
                  DATE_FORMAT(ve.created_date, '%d/%m/%Y') as created_date,
                  ve.id_user,
                  us.user
                  from nc_verificacion ve
                  join sec_users us on ve.id_user = us.id_user
                  where ve.id_no_conformidad = :id_no_conformidad
                  order by ve.created_date asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_no_conformidad', $id_no_conformidad);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_verificacion)
        {$rta = $this->updateVerificacion();}
        else
        {$rta =$this->insertVerificacion();}
        return $rta;
    }


    public function updateVerificacion(){ //ok
        $stmt=new sQuery();
        $query="update nc_verificacion set verificacion_eficacia= :verificacion_eficacia,
                fecha_verificacion = STR_TO_DATE(:fecha_verificacion, '%d/%m/%Y')
                where id_verificacion = :id_verificacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':verificacion_eficacia', $this->getVerificacionEficacia());
        $stmt->dpBind(':fecha_verificacion', $this->getFechaVerificacion());
        $stmt->dpBind(':id_verificacion', $this->getIdVerificacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertVerificacion(){ //ok
        $stmt=new sQuery();
        $query="insert into nc_verificacion(id_no_conformidad, verificacion_eficacia, fecha_verificacion, id_user, created_date)
                values(:id_no_conformidad, :verificacion_eficacia, STR_TO_DATE(:fecha_verificacion, '%d/%m/%Y'), :id_user, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_no_conformidad', $this->getIdNoConformidad());
        $stmt->dpBind(':verificacion_eficacia', $this->getVerificacionEficacia());
        $stmt->dpBind(':fecha_verificacion', $this->getFechaVerificacion());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteVerificacion(){ //ok
        $stmt=new sQuery();
        $query="delete from nc_verificacion where id_verificacion = :id_verificacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_verificacion', $this->getIdVerificacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}




?>