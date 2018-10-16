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
            $query = "select id_tarea, nombre
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
        $query = "select ot.id_tarea,
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
        if($this->id_parte_orden)
        {$rta = $this->updateParteOrden();}
        else
        {$rta =$this->insertParteOrden();}
        return $rta;
    }


    public function updateParteOrden(){ //ok
        $stmt=new sQuery();
        $query="update nov_parte_orden set nro_parte_diario = :nro_parte_diario, orden_tipo = :orden_tipo, orden_nro = :orden_nro,
                duracion = :duracion, servicio = :servicio
                where id_parte_orden = :id_parte_orden";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_parte_diario', $this->getNroParteDiario());
        $stmt->dpBind(':orden_tipo', $this->getOrdenTipo());
        $stmt->dpBind(':orden_nro', $this->getOrdenNro());
        $stmt->dpBind(':duracion', $this->getDuracion());
        $stmt->dpBind(':servicio', $this->getServicio());
        $stmt->dpBind(':id_parte_orden', $this->getIdParteOrden());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertParteOrden(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_parte_orden(fecha, id_parte, nro_parte_diario, orden_tipo, orden_nro, duracion, servicio)
                values(sysdate(), :id_parte, :nro_parte_diario, :orden_tipo, :orden_nro, :duracion, :servicio)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_parte_diario', $this->getNroParteDiario());
        $stmt->dpBind(':orden_tipo', $this->getOrdenTipo());
        $stmt->dpBind(':orden_nro', $this->getOrdenNro());
        $stmt->dpBind(':duracion', $this->getDuracion());
        $stmt->dpBind(':servicio', $this->getServicio());
        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteParteOrden(){ //ok
        $stmt=new sQuery();
        $query="delete from nov_parte_orden where id_parte_orden = :id_parte_orden";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte_orden', $this->getIdParteOrden());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkEmpleado($id_cuadrilla_empleado, $id_cuadrilla, $id_empleado) {
        $stmt=new sQuery();
        $query = "select *
                  from nov_cuadrilla_empleado
                  where id_cuadrilla = :id_cuadrilla
                  and id_empleado = :id_empleado
                  and id_cuadrilla_empleado <> :id_cuadrilla_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_cuadrilla', $id_cuadrilla);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_cuadrilla_empleado', $id_cuadrilla_empleado);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }



}




?>