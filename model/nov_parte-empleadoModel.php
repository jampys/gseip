<?php

class ParteEmpleado
{
    private $id_parte_empleado;
    private $id_parte;
    private $id_empleado;
    private $conductor;
    private $created_by;
    private $created_date; //fecha de registro en el sistema
    private $avoid_event;
    private $comentario;
    private $id_evento;

    // GETTERS
    function getIdParteEmpleado()
    { return $this->id_parte_empleado;}

    function getIdParte()
    { return $this->id_parte;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getConductor()
    { return $this->conductor;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}

    function getAvoidEvent()
    { return $this->avoid_event;}

    function getComentario()
    { return $this->comentario;}

    function getIdEvento()
    { return $this->id_evento;}


    //SETTERS
    function setIdParteEmpleado($val)
    { $this->id_parte_empleado=$val;}

    function setIdParte($val)
    {  $this->id_parte=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setConductor($val)
    { $this->conductor=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}

    function setAvoidEvent($val)
    { $this->avoid_event=$val;}

    function setComentario($val)
    { $this->comentario=$val;}

    function setIdEvento($val)
    { $this->id_evento=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_parte_empleado,
                      id_parte, id_empleado, conductor, avoid_event, comentario, id_evento
                      from nov_parte_empleado
                      where id_parte_empleado = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParteEmpleado($rows[0]['id_parte_empleado']);
            $this->setIdParte($rows[0]['id_parte']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setConductor($rows[0]['conductor']);
            $this->setAvoidEvent($rows[0]['avoid_event']);
            $this->setComentario($rows[0]['comentario']);
            $this->setIdEvento($rows[0]['id_evento']);
        }
    }


    public static function getParteEmpleado($id_parte) { //ok
        $stmt=new sQuery();
        $query = "select npe.id_parte_empleado,
                  npe.id_parte, npe.id_empleado, npe.conductor,
                  em.apellido, em.nombre, em.legajo, em.id_convenio,
                  nc.codigo as convenio
                  from nov_parte_empleado npe
                  join empleados em on npe.id_empleado = em.id_empleado
                  left join nov_convenios nc on em.id_convenio = nc.id_convenio
                  where npe.id_parte = :id_parte
                  order by npe.conductor desc, em.apellido asc, em.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $id_parte);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_parte_empleado)
        {$rta = $this->updateParteEmpleado();}
        else
        {$rta =$this->insertParteEmpleado();}
        return $rta;
    }


    public function updateParteEmpleado(){ //ok
        $stmt=new sQuery();
        $query="update nov_parte_empleado set id_empleado = :id_empleado, conductor = :conductor,
                avoid_event = :avoid_event, comentario = :comentario
                where id_parte_empleado = :id_parte_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':conductor', $this->getConductor());
        $stmt->dpBind(':avoid_event', $this->getAvoidEvent());
        $stmt->dpBind(':comentario', $this->getComentario());
        $stmt->dpBind(':id_parte_empleado', $this->getIdParteEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertParteEmpleado(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_parte_empleado(id_parte, id_empleado, conductor, created_by, created_date, avoid_event, comentario)
                values(:id_parte, :id_empleado, :conductor, :created_by, sysdate(), :avoid_event, :comentario)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':conductor', $this->getConductor());
        $stmt->dpBind(':avoid_event', $this->getAvoidEvent());
        $stmt->dpBind(':comentario', $this->getComentario());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteParteEmpleado(){ //ok
        $stmt=new sQuery();
        $query="delete from nov_parte_empleado where id_parte_empleado = :id_parte_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte_empleado', $this->getIdParteEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkEmpleado($id_parte_empleado, $id_parte) { //No se usa
        $stmt=new sQuery();
        /*$query = "select *
                  from nov_cuadrilla_empleado
                  where id_cuadrilla = :id_cuadrilla
                  and id_empleado = :id_empleado
                  and id_cuadrilla_empleado <> :id_cuadrilla_empleado";*/
        $query = "select 1
                  from nov_parte_empleado
                  where id_parte = :id_parte
                  and conductor = 1
                  and id_parte_empleado <> :id_parte_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $id_parte);
        $stmt->dpBind(':id_parte_empleado', $id_parte_empleado);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }



}




?>