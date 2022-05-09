<?php

class Indicador
{
    private $id_indicador;
    private $indicador;
    private $codigo;
    private $descripcion;
    private $disabled;
    private $created_date;

    // GETTERS
    function getIdIndicador()
    { return $this->id_indicador;}

    function getIndicador()
    { return $this->indicador;}

    function getCodigo()
    { return $this->codigo;}

    function getDescripcion()
    { return $this->descripcion;}

    function getDisabled()
    { return $this->disabled;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdIndicador($val)
    { $this->id_indicador=$val;}

    function setIndicador($val)
    { $this->indicador=$val;}

    function setCodigo($val)
    { $this->codigo=$val;}

    function setDescripcion($val)
    {  $this->descripcion=$val;}

    function setDisabled($val)
    { $this->disabled=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_indicador, indicador, codigo, descripcion, disabled,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from obj_indicadores
                      where id_indicador = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdIndicador($rows[0]['id_indicador']);
            $this->setIndicador($rows[0]['indicador']);
            $this->setCodigo($rows[0]['codigo']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setDisabled($rows[0]['disabled']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getIndicadores($disabled = 0) { //ok
        //trae todas los indicadores. Por defecto trae todos. Si disabled = 1 trae solo los activos
        $stmt=new sQuery();
        $query = "select id_indicador, indicador, codigo, descripcion, disabled,
                  DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                  from obj_indicadores
                  where if(:disabled = 1, disabled is not null, 1)
                  order by disabled asc, indicador asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':disabled', $disabled);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){
        if($this->id_tarea)
        {$rta = $this->updateTarea();}
        else
        {$rta =$this->insertTarea();}
        return $rta;
    }


    public function updateTarea(){
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


    public function insertTarea(){
        $stmt=new sQuery();
        $query="insert into obj_tareas(nombre, descripcion, fecha_inicio, fecha_fin, id_objetivo)
                values(:nombre, :descripcion, STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'), STR_TO_DATE(:fecha_fin, '%d/%m/%Y'), :id_objetivo)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':fecha_inicio', $this->getFechaInicio());
        $stmt->dpBind(':fecha_fin', $this->getFechaFin());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteTarea(){
        $stmt=new sQuery();
        $query="delete from obj_tareas where id_tarea = :id_tarea";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_tarea', $this->getIdTarea());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }





}




?>