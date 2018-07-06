<?php

class CuadrillaEmpleado
{
    private $id_cuadrilla_empleado;
    private $fecha; //fecha de registro en el sistema
    private $id_cuadrilla;
    private $id_empleado;

    // GETTERS
    function getIdCuadrillaEmpleado()
    { return $this->id_cuadrilla_empleado;}

    function getFecha()
    { return $this->fecha;}

    function getIdCuadrilla()
    { return $this->id_cuadrilla;}

    function getIdEmpleado()
    { return $this->id_empleado;}


    //SETTERS
    function setIdCuadrillaEmpleado($val)
    { $this->id_cuadrilla_empleado=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIdCuadrilla($val)
    {  $this->id_cuadrilla=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_cuadrilla_empleado,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      id_cuadrilla, id_empleado
                      from nov_cuadrilla_empleado
                      where id_cuadrilla_empleado = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdCuadrillaEmpleado($rows[0]['id_cuadrilla_empleado']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdCuadrilla($rows[0]['id_cuadrilla']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
        }
    }


    public static function getCuadrillaEmpleado($id_cuadrilla) { //ok
        $stmt=new sQuery();
        $query = "select nce.id_cuadrilla_empleado,
                  DATE_FORMAT(nce.fecha, '%d/%m/%Y') as fecha,
                  nce.id_cuadrilla, nce.id_empleado,
                  em.apellido, em.nombre
                  from nov_cuadrilla_empleado nce
                  join empleados em on nce.id_empleado = em.id_empleado
                  where nce.id_cuadrilla = :id_cuadrilla
                  order by em.apellido asc, em.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_cuadrilla', $id_cuadrilla);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_cuadrilla_empleado)
        {$rta = $this->updateCuadrillaEmpleado();}
        else
        {$rta =$this->insertCuadrillaEmpleado();}
        return $rta;
    }


    public function updateCuadrillaEmpleado(){ //ok
        $stmt=new sQuery();
        $query="update nov_cuadrilla_empleado set id_empleado = :id_empleado
                where id_cuadrilla_empleado = :id_cuadrilla_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_cuadrilla_empleado', $this->getIdCuadrillaEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    private function insertCuadrillaEmpleado(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_cuadrilla_empleado(fecha, id_cuadrilla, id_empleado)
                values(sysdate(), :id_cuadrilla, :id_empleado)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_cuadrilla', $this->getIdCuadrilla());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteCuadrillaEmpleado(){ //ok
        $stmt=new sQuery();
        $query="delete from nov_cuadrilla_empleado where id_cuadrilla_empleado = :id_cuadrilla_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_cuadrilla_empleado', $this->getIdCuadrillaEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkEmpleado($id_cuadrilla_empleado, $id_cuadrilla, $id_empleado) { //ok
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