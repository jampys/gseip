<?php

class Edicion
{
    private $id_edicion;
    private $id_capacitacion;
    private $nombre;
    private $fecha_edicion;
    private $capacitador;
    private $duracion;
    private $id_modalidad;
    private $id_user;
    private $created_date;

    // GETTERS
    function getIdEdicion()
    { return $this->id_edicion;}

    function getIdCapacitacion()
    { return $this->id_capacitacion;}

    function getNombre()
    { return $this->nombre;}

    function getFechaEdicion()
    { return $this->fecha_edicion;}

    function getCapacitador()
    { return $this->capacitador;}

    function getDuracion()
    { return $this->duracion;}

    function getIdModalidad()
    { return $this->id_modalidad;}

    function getIdUser()
    { return $this->id_user;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdEdicion($val)
    { $this->id_edicion=$val;}

    function setIdCapacitacion($val)
    { $this->id_capacitacion=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setFechaEdicion($val)
    {  $this->fecha_edicion=$val;}

    function setCapacitador($val)
    {  $this->capacitador=$val;}

    function setDuracion($val)
    {  $this->duracion=$val;}

    function setIdModalidad($val)
    { $this->id_modalidad=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select ed.id_edicion, ed.id_capacitacion, ed.nombre,
                      DATE_FORMAT(ed.fecha_edicion, '%d/%m/%Y') as fecha_edicion,
                      ed.capacitador, ed.duracion, ed.id_modalidad, ed.id_user,
                      DATE_FORMAT(ed.created_date, '%d/%m/%Y') as created_date
                      from cap_ediciones ed
                      where id_edicion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEdicion($rows[0]['id_edicion']);
            $this->setIdCapacitacion($rows[0]['id_capacitacion']);
            $this->setNombre($rows[0]['nombre']);
            $this->setFechaEdicion($rows[0]['fecha_edicion']);
            $this->setCapacitador($rows[0]['capacitador']);
            $this->setDuracion($rows[0]['duracion']);
            $this->setIdModalidad($rows[0]['id_modalidad']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getEdiciones($id_capacitacion) { //ok
        $stmt=new sQuery();
        $query = "select ed.id_edicion, ed.id_capacitacion, ed.nombre,
                  DATE_FORMAT(ed.fecha_edicion, '%d/%m/%Y') as fecha_edicion,
                  ed.capacitador, ed.duracion, ed.id_modalidad, ed.id_user,
                  DATE_FORMAT(ed.created_date, '%d/%m/%Y') as created_date,
                  concat(DATE_FORMAT(ed.fecha_edicion, '%d/%m/%Y'), ' ', ed.nombre) as edicion,
                  us.user
                  from cap_ediciones ed
                  join sec_users us on ed.id_user = us.id_user
                  where ed.id_capacitacion = :id_capacitacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_capacitacion', $id_capacitacion);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_edicion)
        {$rta = $this->updateEdicion();}
        else
        {$rta =$this->insertEdicion();}
        return $rta;
    }


    public function updateEdicion(){ //ok
        $stmt=new sQuery();
        $query="update cap_edicion set nombre= :nombre,
                fecha_edicion= STR_TO_DATE(:fecha_edicion, '%d/%m/%Y'),
                capacitador= :capacitador,
                duracion= :duracion,
                id_modalidad= :id_modalidad
                where id_edicion = :id_edicion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_edicion', $this->getFechaEdicion());
        $stmt->dpBind(':capacitador', $this->getCapacitador());
        $stmt->dpBind(':duracion', $this->getDuracion());
        $stmt->dpBind(':id_modalidad', $this->getIdModalidad());
        $stmt->dpBind(':id_edicion', $this->getIdEdicion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertEdicion(){
        $stmt=new sQuery();
        $query="insert into cap_edicion(id_capacitacion, nombre, fecha_edicion, capacitador, duracion, id_modalidad, id_user, created_date)
                values(:id_capacitacion, :nombre, :fecha_edicion, :capacitador, :duracion, :id_modalidad, :id_user, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_capacitacion', $this->getNombre());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_edicion', $this->getFechaEdicion());
        $stmt->dpBind(':capacitador', $this->getCapacitador());
        $stmt->dpBind(':duracion', $this->getDuracion());
        $stmt->dpBind(':id_modalidad', $this->getIdModalidad());
        $stmt->dpBind(':id_edicion', $this->getIdEdicion());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteEdicion(){ //ok
        $stmt=new sQuery();
        $query="delete from cap_ediciones where id_edicion = :id_edicion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_edicion', $this->getIdEdicion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}




?>