<?php


class Vehiculo
{
    private $id_vehiculo;
    private $nro_movil;
    private $matricula;
    private $marca;
    private $modelo;
    private $modelo_ano;
    private $fecha_baja;
    private $propietario;
    private $leasing;
    private $responsable;


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

    function getModeloAno()
    { return $this->modelo_ano;}

    function getFechaBaja()
    { return $this->fecha_baja;}

    function getPropietario()
    { return $this->propietario;}

    function getLeasing()
    { return $this->leasing;}

    function getResponsable()
    { return $this->responsable;}


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

    function setModeloAno($val)
    {  $this->modelo_ano=$val;}

    function setFechaBaja($val)
    {  $this->fecha_baja=$val;}

    function setPropietario($val)
    {  $this->propietario=$val;}

    function setLeasing($val)
    {  $this->leasing=$val;}

    function setResponsable($val)
    {  $this->responsable=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select ve.id_vehiculo, ve.nro_movil, ve.matricula, ve.marca, ve.modelo, ve.modelo_ano,
                    ve.propietario, ve.leasing,
                    DATE_FORMAT(ve.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                    ve.responsable, ve.id_object
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
            $this->setModeloAno($rows[0]['modelo_ano']);
            $this->setFechaBaja($rows[0]['fecha_baja']);
            $this->setPropietario($rows[0]['propietario']);
            $this->setLeasing($rows[0]['leasing']);
            $this->setResponsable($rows[0]['responsable']);
        }
    }


    public static function getVehiculos() { //ok
        $stmt=new sQuery();
        $query="select ve.id_vehiculo, ve.nro_movil, ve.matricula, ve.marca, ve.modelo, ve.modelo_ano,
                DATE_FORMAT(ve.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                ve.responsable,
                co.nombre as propietario
                from vto_vehiculos ve
                left join companias co on co.id_compania = ve.propietario
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
co.nombre as contrato, loc.ciudad as localidad,
DATE_FORMAT(vvc.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(vvc.fecha_hasta,  '%d/%m/%Y') as fecha_hasta
from vto_vehiculo_contrato vvc
join contratos co on vvc.id_contrato = co.id_contrato
left join localidades loc on vvc.id_localidad = loc.id_localidad
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

    public function updateVehiculo(){ //ok

        $stmt=new sQuery();
        $query="update vto_vehiculos set
                nro_movil = :nro_movil,
                matricula = :matricula,
                marca = :marca,
                modelo = :modelo,
                modelo_ano = :modelo_ano,
                propietario = :propietario,
                leasing = :leasing,
                fecha_baja = STR_TO_DATE(:fecha_baja, '%d/%m/%Y'),
                responsable = :responsable
                where id_vehiculo = :id_vehiculo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_movil', $this->getNroMovil());
        $stmt->dpBind(':matricula', $this->getMatricula());
        $stmt->dpBind(':marca', $this->getMarca());
        $stmt->dpBind(':modelo', $this->getModelo());
        $stmt->dpBind(':modelo_ano', $this->getModeloAno());
        $stmt->dpBind(':propietario', $this->getPropietario());
        $stmt->dpBind(':leasing', $this->getLeasing());
        $stmt->dpBind(':fecha_baja', $this->getFechaBaja());
        $stmt->dpBind(':responsable', $this->getResponsable());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertVehiculo(){ //ok

        $stmt=new sQuery();
        $query="insert into vto_vehiculos(nro_movil, matricula, marca, modelo, modelo_ano, propietario, leasing, fecha_baja, responsable)
                values(:nro_movil, :matricula, :marca, :modelo, :modelo_ano, :propietario, :leasing, STR_TO_DATE(:fecha_baja, '%d/%m/%Y'), :responsable)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_movil', $this->getNroMovil());
        $stmt->dpBind(':matricula', $this->getMatricula());
        $stmt->dpBind(':marca', $this->getMarca());
        $stmt->dpBind(':modelo', $this->getModelo());
        $stmt->dpBind(':modelo_ano', $this->getModeloAno());
        $stmt->dpBind(':propietario', $this->getPropietario());
        $stmt->dpBind(':leasing', $this->getLeasing());
        $stmt->dpBind(':fecha_baja', $this->getFechaBaja());
        $stmt->dpBind(':responsable', $this->getResponsable());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteVehiculo(){
        $stmt=new sQuery();
        $query="delete from vto_vehiculos where id_vehiculo = :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdVehiculo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public function checkVehiculoMatricula($matricula, $id_vehiculo) { //ok
        $stmt=new sQuery();
        $query = "select * from vto_vehiculos ve
                  where ve.matricula = :matricula
                  and
                  (( -- nuevo vehiculo
                  :id_vehiculo is null
                  -- no se ponen condiciones
                  )
                  OR
                  ( -- edicion vehiculo
                  :id_vehiculo is not null
                  and ve.id_vehiculo <> :id_vehiculo
                  ))";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':matricula', $matricula);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }


    public function checkVehiculoNroMovil($nro_movil, $id_vehiculo) { //ok
        $stmt=new sQuery();
        $query = "select * from vto_vehiculos ve
                  where ve.nro_movil = :nro_movil
                  and
                  (( -- nuevo vehiculo
                  :id_vehiculo is null
                  -- no se ponen condiciones
                  )
                  OR
                  ( -- edicion vehiculo
                  :id_vehiculo is not null
                  and ve.id_vehiculo <> :id_vehiculo
                  ))";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_movil', $nro_movil);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }


    public function getSeguroVehicular() {
        //3: seguro vehicular
        $stmt=new sQuery();
        /*$query = "select 'Seguro individual ' as tipo_seguro,v.referencia,
DATE_FORMAT(v.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(v.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento
from vto_renovacion_v v
where v.id_vehiculo = :id_vehiculo
and v.id_vencimiento = 3
and v.id_rnv_renovacion is null
and v.disabled is null
UNION
select 'Seguro grupal ' as tipo_seguro, CONCAT(g.nombre, ' ', g.nro_referencia) as referencia,
DATE_FORMAT(v.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(ifnull(gv.fecha_hasta, v.fecha_vencimiento),  '%d/%m/%Y') as fecha_vencimiento
from vto_grupos_v g
join vto_grupo_vehiculo gv on g.id_grupo = gv.id_grupo
join vto_renovacion_v v on v.id_grupo = g.id_grupo
where gv.id_vehiculo = :id_vehiculo
and v.id_vencimiento = 3
and v.id_rnv_renovacion is null
and v.disabled is null";*/
        $query = "select 'Seguro individual ' as tipo_seguro,v.referencia,
DATE_FORMAT(v.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(v.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento
from vto_renovacion_v v
where v.id_vehiculo = :id_vehiculo
and v.id_vencimiento = 3
and v.id_rnv_renovacion is null
and v.disabled is null
UNION
select 'Seguro grupal ' as tipo_seguro, CONCAT(g.nombre, ' ', g.nro_referencia) as referencia,
DATE_FORMAT(if(gv.fecha_hasta is null or gv.fecha_hasta >= sysdate(), v.fecha_emision, gv.fecha_desde),  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(if(gv.fecha_hasta is null or gv.fecha_hasta >= sysdate(), v.fecha_vencimiento, gv.fecha_hasta),  '%d/%m/%Y') as fecha_vencimiento
from vto_grupos_v g
join vto_grupo_vehiculo gv on g.id_grupo = gv.id_grupo
join vto_renovacion_v v on v.id_grupo = g.id_grupo
where gv.id_vehiculo = :id_vehiculo
and v.id_vencimiento = 3
and v.id_rnv_renovacion is null
and v.disabled is null";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}



?>