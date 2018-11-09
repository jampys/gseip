<?php

class GrupoVehiculo
{
    private $id_grupo_vehiculo;
    private $id_vehiculo;
    private $id_grupo;
    private $fecha_desde;
    private $fecha_hasta;

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


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_grupo_vehiculo, id_vehiculo, id_grupo,
                      DATE_FORMAT(fecha_desde, '%d/%m/%Y') as fecha_desde,
                      DATE_FORMAT(fecha_hasta, '%d/%m/%Y') as fecha_hasta
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
        }
    }

    //trae los vehiculos de un determinado grupo
    public static function getVehiculos($id_grupo) {  //ok
        $stmt=new sQuery();
        $query = "select gv.id_grupo_vehiculo, gv.id_vehiculo, gv.id_grupo,
                  DATE_FORMAT(gv.fecha_desde, '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(gv.fecha_hasta, '%d/%m/%y') as fecha_hasta,
                  ve.matricula, ve.nro_movil
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


    public function updateGrupoVehiculo(){
        $stmt=new sQuery();
        $query="update sel_etapas set fecha_etapa = STR_TO_DATE(:fecha_etapa, '%d/%m/%Y'),
                etapa = :etapa,
                aplica = :aplica,
                motivo = :motivo,
                modo_contacto = :modo_contacto,
                comentarios = :comentarios
                where id_etapa = :id_etapa";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_etapa', $this->getFechaEtapa());
        $stmt->dpBind(':etapa', $this->getEtapa());
        $stmt->dpBind(':aplica', $this->getAplica());
        $stmt->dpBind(':motivo', $this->getMotivo());
        $stmt->dpBind(':modo_contacto', $this->getModoContacto());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_etapa', $this->getIdEtapa());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertGrupoVehiculo(){
        $stmt=new sQuery();
        $query="insert into sel_etapas(id_postulacion, fecha, fecha_etapa, etapa, aplica, motivo , modo_contacto, comentarios, id_user)
                values(:id_postulacion, sysdate(), STR_TO_DATE(:fecha_etapa, '%d/%m/%Y'), :etapa, :aplica, :motivo, :modo_contacto, :comentarios, :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulacion', $this->getIdPostulacion());
        $stmt->dpBind(':fecha_etapa', $this->getFechaEtapa());
        $stmt->dpBind(':etapa', $this->getEtapa());
        $stmt->dpBind(':aplica', $this->getAplica());
        $stmt->dpBind(':motivo', $this->getMotivo());
        $stmt->dpBind(':modo_contacto', $this->getModoContacto());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteGrupoVehiculo(){
        $stmt=new sQuery();
        $query="delete from sel_etapas where id_etapa = :id_etapa";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_etapa', $this->getIdEtapa());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkDni($dni, $id_postulante) {
        $stmt=new sQuery();
        $query = "select *
                  from sel_postulantes
                  where dni = :dni
                  and id_postulante <> :id_postulante";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':dni', $dni);
        $stmt->dpBind(':id_postulante', $id_postulante);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }



}




?>