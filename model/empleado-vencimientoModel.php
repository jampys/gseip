<?php

class EmpleadoVencimiento
{
    private $id_empleado_vencimiento;
    private $id_empleado;
    private $id_vencimiento;
    private $created_date;

    // GETTERS
    function getIdEmpleadoVencimiento()
    { return $this->id_empleado_vencimiento;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdEmpleadoVencimiento($val)
    { $this->id_empleado_vencimiento=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setIdVencimiento($val)
    {  $this->id_vencimiento=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_empleado_vencimiento, id_empleado, id_vencimiento,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from empleado_vencimiento
                      where id_empleado_vencimiento = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEmpleadoVencimiento($rows[0]['id_empleado_vencimiento']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdVencimiento($rows[0]['id_vencimiento']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getEmpleadoVencimiento($id_empleado) { //ok
        $stmt=new sQuery();
        $query = "select v.id_vencimiento, v.nombre, ev.id_empleado_vencimiento, ev.id_empleado, ev.created_date
from vto_vencimiento_p v
left join empleado_vencimiento ev on v.id_vencimiento = ev.id_vencimiento and ev.id_empleado = :id_empleado
order by v.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    /*function save(){
        if($this->id_cuadrilla_empleado)
        {$rta = $this->updateCuadrillaEmpleado();}
        else
        {$rta =$this->insertCuadrillaEmpleado();}
        return $rta;
    }*/


    /*public function updateCuadrillaEmpleado(){
        $stmt=new sQuery();
        $query="update nov_cuadrilla_empleado set id_empleado = :id_empleado,
                conductor = :conductor
                where id_cuadrilla_empleado = :id_cuadrilla_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_cuadrilla_empleado', $this->getIdCuadrillaEmpleado());
        $stmt->dpBind(':conductor', $this->getConductor());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }*/


    public function insertEmpleadoVencimiento(){ //ok
        $stmt=new sQuery();
        $query="insert into empleado_vencimiento(id_empleado, id_vencimiento, created_date)
                values(:id_empleado, :id_vencimiento, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function deleteEmpleadoVencimiento(){ //ok
        $stmt=new sQuery();
        $query="delete from empleado_vencimiento where id_empleado_vencimiento = :id_empleado_vencimiento";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado_vencimiento', $this->getIdEmpleadoVencimiento());
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