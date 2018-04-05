<?php


class Vehiculo
{
    private $id_vehiculo;
    private $nro_movil;
    private $matricula;
    private $marca;
    private $modelo;
    private $modelo_año;
    private $fecha_baja;


    // GETTERS
    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getNroMovil()
    { return $this->nro_movil;}

    function getMatricula()
    { return $this->matricula;}

    function getMarca()
    { return $this->marca;}

    function getModelo()
    { return $this->modelo;}

    function getModeloAño()
    { return $this->modelo_año;}

    function getFechaBaja()
    { return $this->fecha_baja;}


    //SETTERS
    function setIdVehiculo($val)
    { $this->id_vehiculo=$val;}

    function setNroMovil($val)
    { $this->nro_movil=$val;}

    function setMatricula($val)
    { $this->matricula=$val;}

    function setMarca($val)
    {  $this->marca=$val;}

    function setModelo($val)
    {  $this->modelo=$val;}

    function setModeloAño($val)
    {  $this->modelo_año=$val;}

    function setFechaBaja($val)
    {  $this->fecha_baja=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select ve.*
                    from vto_vehiculos ve
                    where ve.id_vehiculo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setNroMovil($rows[0]['nro_movil']);
            $this->setMatricula($rows[0]['matricula']);
            $this->setMarca($rows[0]['marca']);
            $this->setModelo($rows[0]['modelo']);
            $this->setModeloAño($rows[0]['modelo_año']);
            $this->setFechaBaja($rows[0]['fecha_baja']);
        }
    }


    public static function getVehiculos() { //ok
        $stmt=new sQuery();
        $query="select ve.id_vehiculo, ve.nro_movil, ve.matricula, ve.marca, ve.modelo, ve.modelo_año, ve.propietario,
ve.fecha_baja, ve.responsable
from vto_vehiculos ve
order by ve.fecha_baja asc, ve.nro_movil asc";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getVehiculosActivos() {
        $stmt=new sQuery();
        $query = "select em.id_empleado, em.legajo, em.apellido, em.nombre, em.documento, em.cuil,
                      DATE_FORMAT(em.fecha_nacimiento,  '%d/%m/%Y') as fecha_nacimiento,
                      DATE_FORMAT(em.fecha_alta,  '%d/%m/%Y') as fecha_alta,
                      DATE_FORMAT(em.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                      em.telefono, em.email, em.empresa,
                      em.sexo, em.nacionalidad, em.estado_civil
                      from empleados em
                      where em.fecha_baja is null";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public function getContratosByVehiculo() { //muestra los contratos con el vehiculo (TODOS: vigentes y no vigentes)
        $stmt=new sQuery();
        $query = "select vvc.id_vehiculo_contrato, vvc.id_vehiculo, vvc.id_contrato,
co.nombre as contrato,
DATE_FORMAT(vvc.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(vvc.fecha_hasta,  '%d/%m/%Y') as fecha_hasta
from vto_vehiculo_contrato vvc
join contratos co on vvc.id_contrato = co.id_contrato
where id_vehiculo = :id_vehiculo
order by vvc.fecha_desde desc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_vehiculo)
        {$rta = $this->updateVehiculo();}
        else
        {$rta =$this->insertVehiculo();}
        return $rta;
    }

    public function updateVehiculo(){

        $stmt=new sQuery();
        $query="update puestos set
                nombre= :nombre,
                descripcion= :descripcion,
                codigo= :codigo,
                id_puesto_superior= :id_puesto_superior,
                id_area= :id_area,
                id_nivel_competencia= :id_nivel_competencia
                where id_puesto = :id_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':id_puesto_superior', $this->getIdPuestoSuperior());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_nivel_competencia', $this->getIdNivelCompetencia());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertVehiculo(){

        $stmt=new sQuery();
        $query="insert into puestos(nombre, descripcion, codigo, id_puesto_superior, id_area, id_nivel_competencia)
                values(:nombre, :descripcion, :codigo, :id_puesto_superior, :id_area, :id_nivel_competencia)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':id_puesto_superior', $this->getIdPuestoSuperior());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_nivel_competencia', $this->getIdNivelCompetencia());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deletePuesto(){
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    

}



?>