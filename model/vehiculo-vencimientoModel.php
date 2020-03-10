<?php

class VehiculoVencimiento
{
    private $id_vehiculo_vencimiento;
    private $id_vehiculo;
    private $id_vencimiento;
    private $created_date;

    // GETTERS
    function getIdVehiculoVencimiento()
    { return $this->id_vehiculo_vencimiento;}

    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdVehiculoVencimiento($val)
    { $this->id_vehiculo_vencimiento=$val;}

    function setIdVehiculo($val)
    { $this->id_vehiculo=$val;}

    function setIdVencimiento($val)
    {  $this->id_vencimiento=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_vehiculo_vencimiento, id_vehiculo, id_vencimiento,
                      DATE_FORMAT(created_date, '%d/%m/%Y') as created_date
                      from vehiculo_vencimiento
                      where id_vehiculo_vencimiento = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdVehiculoVencimiento($rows[0]['id_vehiculo_vencimiento']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdVencimiento($rows[0]['id_vencimiento']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getVehiculoVencimiento($id_vehiculo) { //ok
        $stmt=new sQuery();
        $query = "select v.id_vencimiento, v.nombre, vv.id_vehiculo_vencimiento, vv.id_vehiculo, vv.created_date
from vto_vencimiento_v v
left join vto_vehiculo_vencimiento vv on v.id_vencimiento = vv.id_vencimiento and vv.id_vehiculo = :id_vehiculo
order by v.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
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


    public function insertVehiculoVencimiento(){ //ok
        $stmt=new sQuery();
        $query="insert into vto_vehiculo_vencimiento(id_vehiculo, id_vencimiento, created_date)
                values(:id_vehiculo, :id_vencimiento, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function deleteVehiculoVencimiento(){ //ok
        $stmt=new sQuery();
        $query="delete from vto_vehiculo_vencimiento where id_vehiculo_vencimiento = :id_vehiculo_vencimiento";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo_vencimiento', $this->getIdVehiculoVencimiento());
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