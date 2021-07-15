<?php

class Avance
{
    private $id_avance;
    private $id_objetivo;
    private $id_tarea;
    private $fecha; //fecha de registro en el sistema
    private $indicador;
    private $cantidad;
    private $comentarios;
    private $cantidad_plan;
    private $periodo;
    private $id_user;

    // GETTERS
    function getIdAvance()
    { return $this->id_avance;}

    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getIdTarea()
    { return $this->id_tarea;}

    function getFecha()
    { return $this->fecha;}

    function getIndicador()
    { return $this->indicador;}

    function getCantidad()
    { return $this->cantidad;}

    function getComentarios()
    { return $this->comentarios;}

    function getCantidadPlan()
    { return $this->cantidad_plan;}

    function getPeriodo()
    { return $this->periodo;}

    function getIdUser()
    { return $this->id_user;}


    //SETTERS
    function setIdAvance($val)
    { $this->id_avance=$val;}

    function setIdObjetivo($val)
    {  $this->id_objetivo=$val;}

    function setIdTarea($val)
    {  $this->id_tarea=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIndicador($val)
    { $this->indicador=$val;}

    function setCantidad($val)
    { $this->cantidad=$val;}

    function setComentarios($val)
    { $this->comentarios=$val;}

    function setCantidadPlan($val)
    { $this->cantidad_plan=$val;}

    function setPeriodo($val)
    { $this->periodo=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_avance, id_objetivo, id_tarea,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      indicador, cantidad, comentarios, cantidad_plan, periodo, id_user
                      from obj_avances
                      where id_avance = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdAvance($rows[0]['id_avance']);
            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setIdTarea($rows[0]['id_tarea']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIndicador($rows[0]['indicador']);
            $this->setCantidad($rows[0]['cantidad']);
            $this->setComentarios($rows[0]['comentarios']);
            $this->setCantidadPlan($rows[0]['cantidad_plan']);
            $this->setPeriodo($rows[0]['periodo']);
            $this->setIdUser($rows[0]['id_user']);
        }
    }


    public static function getAvances($id_objetivo, $id_tarea) { //ok
        $stmt=new sQuery();
        $query = "select av.id_avance, av.id_objetivo, av.id_tarea,
                  DATE_FORMAT(av.fecha, '%d/%m/%Y') as fecha,
                  av.indicador, av.cantidad, av.comentarios, av.id_user,
                  ot.nombre as tarea,
                  us.user
                  from obj_avances av
                  join sec_users us on av.id_user = us.id_user
                  left join obj_tareas ot on av.id_tarea = ot.id_tarea
                  where av.id_objetivo = :id_objetivo
                  -- and av.id_tarea = ifnull(:id_tarea, av.id_tarea)
                  and if(:id_tarea is null, 1 , av.id_tarea = :id_tarea)
                  order by av.fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $id_objetivo);
        $stmt->dpBind(':id_tarea', $id_tarea);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_avance)
        {$rta = $this->updateAvance();}
        else
        {$rta =$this->insertAvance();}
        return $rta;
    }


    public function updateAvance(){ //ok
        $stmt=new sQuery();
        $query="update obj_avances set fecha = STR_TO_DATE(:fecha, '%d/%m/%Y'),
                id_tarea = :id_tarea,
                indicador = :indicador,
                cantidad = :cantidad,
                comentarios = :comentarios
                where id_avance = :id_avance";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha', $this->getFecha());
        $stmt->dpBind(':id_tarea', $this->getIdTarea());
        $stmt->dpBind(':indicador', $this->getIndicador());
        $stmt->dpBind(':cantidad', $this->getCantidad());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_avance', $this->getIdAvance());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertAvance(){ //ok
        $stmt=new sQuery();
        $query="insert into obj_avances(id_objetivo, fecha, id_tarea, indicador, cantidad, comentarios, id_user)
                values(:id_objetivo, STR_TO_DATE(:fecha, '%d/%m/%Y'), :id_tarea, :indicador, :cantidad, :comentarios, :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpBind(':fecha', $this->getFecha());
        $stmt->dpBind(':id_tarea', $this->getIdTarea());
        $stmt->dpBind(':indicador', $this->getIndicador());
        $stmt->dpBind(':cantidad', $this->getCantidad());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteAvance(){ //ok
        $stmt=new sQuery();
        $query="delete from obj_avances where id_avance = :id_avance";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_avance', $this->getIdAvance());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



}




?>