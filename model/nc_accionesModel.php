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
                  DATE_FORMAT(ac.created_date, '%d/%m/%y %H:%i') as created_date,
                  ac.id_user,
                  us.user
                  from nc_accion ac
                  join sec_users us on ac.id_user = us.id_user
                  where ac.id_no_conformidad = :id_no_conformidad
                  order by ac.fecha_implementacion asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_no_conformidad', $id_no_conformidad);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_accion)
        {$rta = $this->updateAccion();}
        else
        {$rta =$this->insertAccion();}
        return $rta;
    }


    public function updateAccion(){ //ok
        $stmt=new sQuery();
        $query="update nc_accion set accion= :accion,
                id_responsable_ejecucion = :id_responsable_ejecucion,
                fecha_implementacion = STR_TO_DATE(:fecha_implementacion, '%d/%m/%Y')
                where id_accion = :id_accion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':accion', $this->getAccion());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':fecha_implementacion', $this->getFechaImplementacion());
        $stmt->dpBind(':id_accion', $this->getIdAccion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertAccion(){ //ok
        $stmt=new sQuery();
        $query="insert into nc_accion(id_no_conformidad, accion, id_responsable_ejecucion, fecha_implementacion, id_user, created_date)
                values(:id_no_conformidad, :accion, :id_responsable_ejecucion, STR_TO_DATE(:fecha_implementacion, '%d/%m/%Y'), :id_user, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_no_conformidad', $this->getIdNoConformidad());
        $stmt->dpBind(':accion', $this->getAccion());
        $stmt->dpBind(':id_responsable_ejecucion', $this->getIdResponsableEjecucion());
        $stmt->dpBind(':fecha_implementacion', $this->getFechaImplementacion());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteAccion(){ //ok
        $stmt=new sQuery();
        $query="delete from nc_accion where id_accion = :id_accion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_accion', $this->getIdAccion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}




?>