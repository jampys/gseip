<?php

class Tarea
{
    private $id_tarea;
    private $nombre;
    private $descripcion;
    private $fecha_inicio;
    private $fecha_fin;
    private $id_objetivo;
    private $id_user;
    private $created_date;

    // GETTERS
    function getIdTarea()
    { return $this->id_tarea;}

    function getNombre()
    { return $this->nombre;}

    function getDescripcion()
    { return $this->descripcion;}

    function getFechaInicio()
    { return $this->fecha_inicio;}

    function getFechaFin()
    { return $this->fecha_fin;}

    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getIdUser()
    { return $this->id_user;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdTarea($val)
    { $this->id_tarea=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setDescripcion($val)
    { $this->descripcion=$val;}

    function setFechaInicio($val)
    {  $this->fecha_inicio=$val;}

    function setFechaFin($val)
    { $this->fecha_fin=$val;}

    function setIdObjetivo($val)
    { $this->id_objetivo=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_tarea, nombre, descripcion,
                      DATE_FORMAT(fecha_inicio, '%d/%m/%Y') as fecha_inicio,
                      DATE_FORMAT(fecha_fin, '%d/%m/%Y') as fecha_fin,
                      DATE_FORMAT(created_date, '%d/%m/%Y %H:%i') as created_date,
                      id_objetivo
                      from obj_tareas
                      where id_tarea = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdTarea($rows[0]['id_tarea']);
            $this->setNombre($rows[0]['nombre']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setFechaInicio($rows[0]['fecha_inicio']);
            $this->setFechaFin($rows[0]['fecha_fin']);
            $this->setIdObjetivo($rows[0]['id_objetivo']);
        }
    }


    public static function getTareas($id_objetivo) { //ok
        //trae todas las tareas de un objetivo
        $stmt=new sQuery();
        $query = "select ot.id_tarea, ot.nombre, ot.descripcion,
                  DATE_FORMAT(ot.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
                  DATE_FORMAT(ot.fecha_fin, '%d/%m/%Y') as fecha_fin,
                  DATE_FORMAT(ot.created_date, '%d/%m/%Y %H:%i') as created_date,
                  ot.id_objetivo,
                  us.user
                  from obj_tareas ot
                  join sec_users us on us.id_user = ot.id_user
                  where ot.id_objetivo = :id_objetivo
                  order by ot.fecha_inicio asc, ot.fecha_fin asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $id_objetivo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_tarea)
        {$rta = $this->updateTarea();}
        else
        {$rta =$this->insertTarea();}
        return $rta;
    }


    public function updateTarea(){ //ok
        $stmt=new sQuery();
        $query="update obj_tareas set nombre = :nombre, descripcion = :descripcion,
                fecha_inicio = STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'),
                fecha_fin = STR_TO_DATE(:fecha_fin, '%d/%m/%Y')
                where id_tarea = :id_tarea";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':fecha_inicio', $this->getFechaInicio());
        $stmt->dpBind(':fecha_fin', $this->getFechaFin());
        $stmt->dpBind(':id_tarea', $this->getIdTarea());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertTarea(){ //ok
        $stmt=new sQuery();
        $query="insert into obj_tareas(nombre, descripcion, fecha_inicio, fecha_fin, id_objetivo, id_user, created_date)
                values(:nombre, :descripcion, STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'), STR_TO_DATE(:fecha_fin, '%d/%m/%Y'), :id_objetivo, :id_user, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':fecha_inicio', $this->getFechaInicio());
        $stmt->dpBind(':fecha_fin', $this->getFechaFin());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteTarea(){ //ok
        $stmt=new sQuery();
        $query="delete from obj_tareas where id_tarea = :id_tarea";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_tarea', $this->getIdTarea());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }





}




?>