<?php

class ParteEmpleado
{
    private $id_parte_empleado;
    private $fecha; //fecha de registro en el sistema
    private $id_parte;
    private $id_empleado;
    private $conductor;

    // GETTERS
    function getIdParteEmpleado()
    { return $this->id_parte_empleado;}

    function getFecha()
    { return $this->fecha;}

    function getIdParte()
    { return $this->id_parte;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getConductor()
    { return $this->conductor;}


    //SETTERS
    function setIdParteEmpleado($val)
    { $this->id_parte_empleado=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIdParte($val)
    {  $this->id_parte=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setConductor($val)
    { $this->conductor=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_parte_empleado,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      id_parte, id_empleado
                      from nov_parte_empleado
                      where id_parte_empleado = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParteEmpleado($rows[0]['id_parte_empleado']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdParte($rows[0]['id_parte']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setConductor($rows[0]['conductor']);
        }
    }


    public static function getParteEmpleado($id_parte) { //ok
        $stmt=new sQuery();
        $query = "select npe.id_parte_empleado,
                  DATE_FORMAT(npe.fecha, '%d/%m/%Y') as fecha,
                  npe.id_parte, npe.id_empleado, npe.conductor,
                  em.apellido, em.nombre
                  from nov_parte_empleado npe
                  join empleados em on npe.id_empleado = em.id_empleado
                  where npe.id_parte = :id_parte
                  order by em.apellido asc, em.nombre asc";
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


    public function updateParteEmpleado(){
        $stmt=new sQuery();
        $query="update nov_cuadrilla_empleado set id_empleado = :id_empleado
                where id_cuadrilla_empleado = :id_cuadrilla_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_cuadrilla_empleado', $this->getIdCuadrillaEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertParteEmpleado(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_parte_empleado(id_parte, id_empleado, conductor)
                values(:id_parte, :id_empleado, :conductor)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':conductor', $this->getConductor());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteCuadrillaEmpleado(){
        $stmt=new sQuery();
        $query="delete from nov_cuadrilla_empleado where id_cuadrilla_empleado = :id_cuadrilla_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_cuadrilla_empleado', $this->getIdCuadrillaEmpleado());
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