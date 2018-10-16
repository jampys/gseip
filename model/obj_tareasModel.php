<?php

class Tarea
{
    private $id_tarea;
    private $nombre;
    private $fecha_inicio;
    private $fecha_fin;
    private $id_objetivo;

    // GETTERS
    function getIdTarea()
    { return $this->id_tarea;}

    function getNombre()
    { return $this->nombre;}

    function getFechaInicio()
    { return $this->fecha_inicio;}

    function getFechaFin()
    { return $this->fecha_fin;}

    function getIdObjetivo()
    { return $this->id_objetivo;}


    //SETTERS
    function setIdTarea($val)
    { $this->id_tarea=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setFechaInicio($val)
    {  $this->fecha_inicio=$val;}

    function setFechaFin($val)
    { $this->fecha_fin=$val;}

    function setIdObjetivo($val)
    { $this->id_objetivo=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_tarea, nombre,
                      DATE_FORMAT(fecha_inicio, '%d/%m/%Y') as fecha_inicio,
                      DATE_FORMAT(fecha_fin, '%d/%m/%Y') as fecha_fin,
                      id_objetivo
                      from obj_tareas
                      where id_tarea = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdTarea($rows[0]['id_tarea']);
            $this->setNombre($rows[0]['nombre']);
            $this->setFechaInicio($rows[0]['fecha_inicio']);
            $this->setFechaFin($rows[0]['fecha_fin']);
            $this->setIdObjetivo($rows[0]['id_objetivo']);
        }
    }


    public static function getTareas($id_objetivo) { //ok
        //trae todas las tareas de un objetivo
        $stmt=new sQuery();
        $query = "select ot.id_tarea, ot.nombre,
                  DATE_FORMAT(ot.fecha_inicio, '%d/%m/%Y') as fecha_inicio,
                  DATE_FORMAT(ot.fecha_fin, '%d/%m/%Y') as fecha_fin,
                  ot.id_objetivo
                  from obj_tareas ot
                  where ot.id_objetivo = :id_objetivo
                  order by fecha_inicio asc";
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
        $query="update obj_tareas set nombre = :nombre,
                fecha_inicio = STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'),
                fecha_fin = STR_TO_DATE(:fecha_fin, '%d/%m/%Y')
                where id_tarea = :id_tarea";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_inicio', $this->getFechaInicio());
        $stmt->dpBind(':fecha_fin', $this->getFechaFin());
        $stmt->dpBind(':id_tarea', $this->getIdTarea());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertTarea(){ //ok
        $stmt=new sQuery();
        $query="insert into obj_tareas(nombre, fecha_inicio, fecha_fin, id_objetivo)
                values(:nombre, STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'), STR_TO_DATE(:fecha_fin, '%d/%m/%Y'), :id_objetivo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_inicio', $this->getFechaInicio());
        $stmt->dpBind(':fecha_fin', $this->getFechaFin());
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