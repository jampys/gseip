<?php
class Subobjetivo
{
    private $id_objetivo_sub;
    private $nombre;
    private $id_objetivo;
    private $id_area;

    //GETTERS
    function getIdObjetivoSub()
    { return $this->id_objetivo_sub;}

    function getNombre()
    { return $this->nombre;}

    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getIdArea()
    { return $this->id_area;}


    //SETTERS
    function setIdObjetivoSub($val)
    { $this->id_objetivo_sub=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setIdObjetivo($val)
    {  $this->id_objetivo=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}



    function __construct($nro=0){ //constructor ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select *
                    from objetivos_sub
                    where id_objetivo_sub = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdObjetivoSub($rows[0]['id_objetivo_sub']);
            $this->setNombre($rows[0]['nombre']);
            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setIdArea($rows[0]['id_area']);
        }
    }

    //Devuelve todos los subobjetivos de un determinado objetivo
    public static function getSubobjetivos($id_objetivo) { //ok
        $stmt=new sQuery();
        $query = "select os.*, ar.nombre as area
                  from objetivos_sub os, areas ar
                  where os.id_area = ar.id_area
                  and id_objetivo = :id_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $id_objetivo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public function updateEmpleadoContrato(){

        $stmt=new sQuery();
        $query="update empleado_contrato
                set id_puesto= :id_puesto,
                fecha_desde= STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                fecha_hasta= STR_TO_DATE(:fecha_hasta, '%d/%m/%Y')
                where id_empleado_contrato = :id_empleado_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_empleado_contrato', $this->getIdEmpleadoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    public function insertEmpleadoContrato(){

        $stmt=new sQuery();
        $query="insert into empleado_contrato(id_empleado, id_contrato, id_puesto, fecha_desde, fecha_hasta)
                values(:id_empleado, :id_contrato, :id_puesto,
                STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public function deleteEmpleadoContrato(){
        $stmt=new sQuery();
        $query="delete from empleado_contrato where id_empleado_contrato= :id_empleado_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado_contrato', $this->getIdEmpleadoContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

}

?>