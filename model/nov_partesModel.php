<?php


class Parte
{
    private $id_parte;
    private $fecha;
    private $fecha_parte;
    private $cuadrilla;
    private $id_area;
    private $id_vehiculo;
    private $id_evento;
    private $id_contrato;

    // GETTERS
    function getIdParte()
    { return $this->id_parte;}

    function getFecha()
    { return $this->fecha;}

    function getFechaParte()
    { return $this->fecha_parte;}

    function getCuadrilla()
    { return $this->cuadrilla;}

    function getIdArea()
    { return $this->id_area;}

    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getIdEvento()
    { return $this->id_evento;}

    function getIdContrato()
    { return $this->id_contrato;}

    //SETTERS
    function setIdParte($val)
    { $this->id_parte=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setFechaParte($val)
    { $this->fecha_parte=$val;}

    function setCuadrilla($val)
    {  $this->cuadrilla=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}

    function setIdVehiculo($val)
    {  $this->id_vehiculo=$val;}

    function setIdEvento($val)
    {  $this->id_evento=$val;}

    function setIdContrato($val)
    {  $this->id_contrato=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_parte,
                    DATE_FORMAT(fecha,  '%d/%m/%Y') as fecha,
                    DATE_FORMAT(fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    cuadrilla, id_area, id_vehiculo, id_evento, id_contrato
                    from nov_partes where id_parte = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParte($rows[0]['id_parte']);
            $this->setFecha($rows[0]['fecha']);
            $this->setFechaParte($rows[0]['fecha_parte']);
            $this->setCuadrilla($rows[0]['cuadrilla']);
            $this->setIdArea($rows[0]['id_area']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdEvento($rows[0]['id_evento']);
            $this->setIdContrato($rows[0]['id_contrato']);
        }
    }


    public static function getPartes($a, $b, $c, $d) { //ok
        $stmt=new sQuery();
        $query="select pa.id_parte,
                    DATE_FORMAT(pa.fecha,  '%d/%m/%Y') as fecha,
                    DATE_FORMAT(pa.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    pa.cuadrilla, pa.id_area, pa.id_vehiculo, pa.id_evento, pa.id_contrato,
                    concat(ar.codigo, ' ', ar.nombre) as area,
                    concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                    nec.nombre as evento,
                    co.nombre as contrato
                    from nov_partes pa
                    left join nov_areas ar on pa.id_area = ar.id_area
                    left join vto_vehiculos ve on pa.id_vehiculo = ve.id_vehiculo
                    left join nov_eventos_c nec on pa.id_evento = nec.id_evento
                    left join contratos co on pa.id_contrato = co.id_contrato
                    order by pa.fecha_parte asc";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){
        if($this->id_puesto)
        {$rta = $this->updatePuesto();}
        else
        {$rta =$this->insertPuesto();}
        return $rta;
    }

    public function updatePuesto(){

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

    private function insertPuesto(){

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


    public function autocompletarPuestos($term) {
        $stmt=new sQuery();
        $query = "select *
                  from puestos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

}



?>