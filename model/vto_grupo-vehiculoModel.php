<?php

class GrupoVehiculo
{
    private $id_grupo_vehiculo;
    private $id_vehiculo;
    private $id_grupo;
    private $fecha_desde;
    private $fecha_hasta;
    private $certificado;

    // GETTERS
    function getIdGrupoVehiculo()
    { return $this->id_grupo_vehiculo;}

    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getIdGrupo()
    { return $this->id_grupo;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getCertificado()
    { return $this->certificado;}


    //SETTERS
    function setIdGrupoVehiculo($val)
    { $this->id_grupo_vehiculo=$val;}

    function setIdVehiculo($val)
    {  $this->id_vehiculo=$val;}

    function setIdGrupo($val)
    { $this->id_grupo=$val;}

    function setFechaDesde($val)
    { $this->fecha_desde=$val;}

    function setFechaHasta($val)
    { $this->fecha_hasta=$val;}

    function setCertificado($val)
    { $this->certificado=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_grupo_vehiculo, id_vehiculo, id_grupo,
                      DATE_FORMAT(fecha_desde, '%d/%m/%Y') as fecha_desde,
                      DATE_FORMAT(fecha_hasta, '%d/%m/%Y') as fecha_hasta,
                      certificado
                      from vto_grupo_vehiculo
                      where id_grupo_vehiculo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdGrupoVehiculo($rows[0]['id_grupo_vehiculo']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdGrupo($rows[0]['id_grupo']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setCertificado($rows[0]['certificado']);
        }
    }

    //trae los vehiculos de un determinado grupo
    public static function getVehiculos($id_grupo) {  //ok
        $stmt=new sQuery();
        $query = "select gv.id_grupo_vehiculo, gv.id_vehiculo, gv.id_grupo,
                  DATE_FORMAT(gv.fecha_desde, '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(gv.fecha_hasta, '%d/%m/%y') as fecha_hasta,
                  ve.matricula, ve.nro_movil, gv.certificado
                  from vto_grupo_vehiculo gv
                  join vto_vehiculos ve on ve.id_vehiculo = gv.id_vehiculo
                  where gv.id_grupo = :id_grupo
                  order by gv.fecha_desde asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_grupo', $id_grupo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_grupo_vehiculo)
        {$rta = $this->updateGrupoVehiculo();}
        else
        {$rta =$this->insertGrupoVehiculo();}
        return $rta;
    }


    public function updateGrupoVehiculo(){ //ok
        $stmt=new sQuery();
        $query="update vto_grupo_vehiculo set id_vehiculo = :id_vehiculo,
                fecha_desde = STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                fecha_hasta = STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                certificado = :certificado
                where id_grupo_vehiculo = :id_grupo_vehiculo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':certificado', $this->getCertificado());
        $stmt->dpBind(':id_grupo_vehiculo', $this->getIdGrupoVehiculo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertGrupoVehiculo(){ //ok
        $stmt=new sQuery();
        $query="insert into vto_grupo_vehiculo(id_vehiculo, id_grupo, fecha_desde, fecha_hasta, certificado)
                values(:id_vehiculo, :id_grupo, STR_TO_DATE(:fecha_desde, '%d/%m/%Y'), STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'), :certificado)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':certificado', $this->getCertificado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteGrupoVehiculo(){ //ok
        $stmt=new sQuery();
        $query="delete from vto_grupo_vehiculo where id_grupo_vehiculo = :id_grupo_vehiculo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_grupo_vehiculo', $this->getIdGrupoVehiculo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkVehiculo($id_vehiculo, $id_grupo_vehiculo) { //ok
        //verifica que el vehiculo no se encuentre activo en ninguno de los grupos
        $stmt=new sQuery();
        $query = "select 1
from vto_grupo_vehiculo gv
left join vto_grupos_v g on g.id_grupo = gv.id_grupo
where gv.id_vehiculo = :id_vehiculo
and gv.fecha_hasta is null
and g.fecha_baja is null
and gv.id_grupo_vehiculo <> :id_grupo_vehiculo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpBind(':id_grupo_vehiculo', $id_grupo_vehiculo);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }



}




?>