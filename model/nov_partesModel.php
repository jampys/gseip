<?php


class Parte
{
    private $id_parte;
    private $fecha_parte;
    private $cuadrilla;
    private $id_area;
    private $id_vehiculo;
    private $id_evento;
    private $id_contrato;
    private $hs_normal;
    private $hs_50;
    private $hs_100;
    private $created_by;
    private $created_date;

    // GETTERS
    function getIdParte()
    { return $this->id_parte;}

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

    function getHsNormal()
    { return $this->hs_normal;}

    function getHs50()
    { return $this->hs_50;}

    function getHs100()
    { return $this->hs_100;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}



    //SETTERS
    function setIdParte($val)
    { $this->id_parte=$val;}

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

    function setHsNormal($val)
    {  $this->hs_normal=$val;}

    function setHs50($val)
    {  $this->hs_50=$val;}

    function setHs100($val)
    {  $this->hs_100=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select id_parte,
                    DATE_FORMAT(fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    cuadrilla, id_area, id_vehiculo, id_evento, id_contrato,
                    TIME_FORMAT(hs_normal, '%H:%i') as hs_normal,
                    TIME_FORMAT(hs_50, '%H:%i') as hs_50,
                    TIME_FORMAT(hs_100, '%H:%i') as hs_100,
                    created_by,
                    DATE_FORMAT(created_date,  '%d/%m/%Y') as created_date
                    from nov_partes where id_parte = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParte($rows[0]['id_parte']);
            $this->setFechaParte($rows[0]['fecha_parte']);
            $this->setCuadrilla($rows[0]['cuadrilla']);
            $this->setIdArea($rows[0]['id_area']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdEvento($rows[0]['id_evento']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setHsNormal($rows[0]['hs_normal']);
            $this->setHs50($rows[0]['hs_50']);
            $this->setHs100($rows[0]['hs_100']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }


    public static function getPartes($fecha_desde, $fecha_hasta, $id_contrato, $d) { //ok
        $stmt=new sQuery();
        $query="select pa.id_parte,
                    DATE_FORMAT(pa.created_date,  '%d/%m/%Y') as created_date,
                    DATE_FORMAT(pa.fecha_parte,  '%d/%m/%Y') as fecha_parte,
                    pa.cuadrilla, pa.id_area, pa.id_vehiculo, pa.id_evento, pa.id_contrato, pa.calculado,
                    concat(ar.codigo, ' ', ar.nombre) as area,
                    concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                    concat(nec.codigo, ' ', nec.nombre) as evento,
                    co.nombre as contrato,
                    us.user
                    from nov_partes pa
                    left join nov_areas ar on pa.id_area = ar.id_area
                    left join vto_vehiculos ve on pa.id_vehiculo = ve.id_vehiculo
                    left join nov_eventos_c nec on pa.id_evento = nec.id_evento
                    left join contratos co on pa.id_contrato = co.id_contrato
                    join sec_users us on pa.created_by = us.id_user
                    and pa.fecha_parte between if(:fecha_desde is null, pa.fecha_parte, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'))
                    and if(:fecha_hasta is null, pa.fecha_parte, STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'))
                    and pa.id_contrato =  ifnull(:id_contrato, pa.id_contrato)
                    order by pa.fecha_parte asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_desde', $fecha_desde);
        $stmt->dpBind(':fecha_hasta', $fecha_hasta);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_parte)
        {$rta = $this->updateParte();}
        else
        {$rta =$this->insertParte();}
        return $rta;
    }


    public function updateParte(){ //ok
        $stmt=new sQuery();
        $query = 'CALL sp_calcularNovedades2(:id_parte,
                                        :id_area,
                                        :id_vehiculo,
                                        :id_evento,
                                        :hs_normal,
                                        :hs_50,
                                        :hs_100,
                                        :created_by,
                                        @flag,
                                        @msg
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':hs_normal', $this->getHsNormal());
        $stmt->dpBind(':hs_50', $this->getHs50());
        $stmt->dpBind(':hs_100', $this->getHs100());
        $stmt->dpBind(':created_by', $this->getCreatedBy());

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag, @msg as msg";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        //$flag = $stmt->dpFetchAll();
        //return ($flag)? intval($flag[0]['flag']) : -1;
        return $stmt->dpFetchAll(); //retorna array bidimensional con flag y msg
    }


    public function insertParte(){ //ok

        $stmt=new sQuery();
        $query="insert into nov_partes(fecha_parte, cuadrilla, id_area, id_vehiculo, id_evento, id_contrato, created_by, created_date)
                values(STR_TO_DATE(:fecha_parte, '%d/%m/%Y'), :cuadrilla, :id_area, :id_vehiculo, :id_evento, :id_contrato, :created_by, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_parte', $this->getFechaParte());
        $stmt->dpBind(':cuadrilla', $this->getCuadrilla());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_evento', $this->getIdEvento());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
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