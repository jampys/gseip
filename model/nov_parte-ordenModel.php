<?php

class ParteOrden
{
    private $id_parte_orden;
    private $id_parte;
    private $nro_parte_diario;
    private $orden_tipo;
    private $orden_nro;
    private $hora_inicio;
    private $hora_fin;
    private $servicio;
    private $created_by;
    private $created_date; //fecha de registro en el sistema


    // GETTERS
    function getIdParteOrden()
    { return $this->id_parte_orden;}

    function getIdParte()
    { return $this->id_parte;}

    function getNroParteDiario()
    { return $this->nro_parte_diario;}

    function getOrdenTipo()
    { return $this->orden_tipo;}

    function getOrdenNro()
    { return $this->orden_nro;}

    function getHoraInicio()
    { return $this->hora_inicio;}

    function getHoraFin()
    { return $this->hora_fin;}

    function getServicio()
    { return $this->servicio;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdParteOrden($val)
    { $this->id_parte_orden=$val;}

    function setIdParte($val)
    {  $this->id_parte=$val;}

    function setNroParteDiario($val)
    { $this->nro_parte_diario=$val;}

    function setOrdenTipo($val)
    { $this->orden_tipo=$val;}

    function setOrdenNro($val)
    { $this->orden_nro=$val;}

    function setHoraInicio($val)
    { $this->hora_inicio=$val;}

    function setHoraFin($val)
    { $this->hora_fin=$val;}

    function setServicio($val)
    { $this->servicio=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_parte_orden,
                      id_parte, nro_parte_diario, orden_tipo, orden_nro, hora_inicio, hora_fin, servicio,
                      created_by,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from nov_parte_orden
                      where id_parte_orden = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParteOrden($rows[0]['id_parte_orden']);
            $this->setIdParte($rows[0]['id_parte']);
            $this->setNroParteDiario($rows[0]['nro_parte_diario']);
            $this->setOrdenTipo($rows[0]['orden_tipo']);
            $this->setOrdenNro($rows[0]['orden_nro']);
            $this->setHoraInicio($rows[0]['hora_inicio']);
            $this->setHoraFin($rows[0]['hora_fin']);
            $this->setServicio($rows[0]['servicio']);
            $this->setCreatedBy($rows[0]['created_by']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getParteOrden($id_parte) { //ok
        $stmt=new sQuery();
        $query = "select npo.id_parte_orden,
                  npo.id_parte, npo.nro_parte_diario, npo.orden_tipo, npo.orden_nro, npo.hora_inicio, hora_fin, npo.servicio,
                  DATE_FORMAT(npo.created_date, '%d/%m/%Y') as created_date
                  from nov_parte_orden npo
                  where npo.id_parte = :id_parte
                  order by created_date asc";
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
                hora_inicio = :hora_inicio, hora_fin = :hora_fin, servicio = :servicio
                where id_parte_orden = :id_parte_orden";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_parte_diario', $this->getNroParteDiario());
        $stmt->dpBind(':orden_tipo', $this->getOrdenTipo());
        $stmt->dpBind(':orden_nro', $this->getOrdenNro());
        $stmt->dpBind(':hora_inicio', $this->getHoraInicio());
        $stmt->dpBind(':hora_fin', $this->getHoraFin());
        $stmt->dpBind(':servicio', $this->getServicio());
        $stmt->dpBind(':id_parte_orden', $this->getIdParteOrden());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertParteOrden(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_parte_orden(id_parte, nro_parte_diario, orden_tipo, orden_nro, hora_inicio, hora_fin, servicio, created_by, created_date)
                values(:id_parte, :nro_parte_diario, :orden_tipo, :orden_nro, :hora_inicio, :hora_fin, :servicio, :created_by, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_parte_diario', $this->getNroParteDiario());
        $stmt->dpBind(':orden_tipo', $this->getOrdenTipo());
        $stmt->dpBind(':orden_nro', $this->getOrdenNro());
        $stmt->dpBind(':hora_inicio', $this->getHoraInicio());
        $stmt->dpBind(':hora_fin', $this->getHoraFin());
        $stmt->dpBind(':servicio', $this->getServicio());
        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
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