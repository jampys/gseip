<?php

class ParteOrden
{
    private $id_parte_orden;
    private $fecha; //fecha de registro en el sistema
    private $id_parte;
    private $nro_parte_diario;
    private $orden_tipo;
    private $orden_nro;
    private $duracion;
    private $servicio;


    // GETTERS
    function getIdParteOrden()
    { return $this->id_parte_orden;}

    function getFecha()
    { return $this->fecha;}

    function getIdParte()
    { return $this->id_parte;}

    function getNroParteDiario()
    { return $this->nro_parte_diario;}

    function getOrdenTipo()
    { return $this->orden_tipo;}

    function getOrdenNro()
    { return $this->orden_nro;}

    function getDuracion()
    { return $this->duracion;}

    function getServicio()
    { return $this->servicio;}


    //SETTERS
    function setIdParteOrden($val)
    { $this->id_parte_orden=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIdParte($val)
    {  $this->id_parte=$val;}

    function setNroParteDiario($val)
    { $this->nro_parte_diario=$val;}

    function setOrdenTipo($val)
    { $this->orden_tipo=$val;}

    function setOrdenNro($val)
    { $this->orden_nro=$val;}

    function setDuracion($val)
    { $this->duracion=$val;}

    function setServicio($val)
    { $this->servicio=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_parte_orden,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      id_parte, nro_parte_diario, orden_tipo, orden_nro, duracion, servicio
                      from nov_parte_orden
                      where id_parte_orden = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParteOrden($rows[0]['id_parte_orden']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdParte($rows[0]['id_parte']);
            $this->setNroParteDiario($rows[0]['nro_parte_diario']);
            $this->setOrdenTipo($rows[0]['orden_tipo']);
            $this->setOrdenNro($rows[0]['orden_nro']);
            $this->setDuracion($rows[0]['duracion']);
            $this->setServicio($rows[0]['servicio']);
        }
    }


    public static function getParteOrden($id_parte) { //ok
        $stmt=new sQuery();
        $query = "select npo.id_parte_orden,
                  DATE_FORMAT(npo.fecha, '%d/%m/%Y') as fecha,
                  npo.id_parte, npo.nro_parte_diario, npo.orden_tipo, npo.orden_nro, npo.duracion, npo.servicio
                  from nov_parte_orden npo
                  where npo.id_parte = :id_parte
                  order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $id_parte);
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