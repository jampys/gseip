<?php
class Capacitacion
{
    private $id_capacitacion;
    private $id_plan_capacitacion;
    private $id_categoria;
    private $tema;
    private $descripcion;
    private $capacitador;
    private $fecha_programada;
    private $duracion;
    private $fecha_inicio;
    private $fecha_fin;
    private $id_modalidad;
    private $created_date;
    private $id_user;

    // GETTERS
    function getIdCapacitacion()
    { return $this->id_capacitacion;}

    function getIdPlanCapacitacion()
    { return $this->id_plan_capacitacion;}

    function getIdCategoria()
    { return $this->id_categoria;}

    function getTema()
    { return $this->tema;}

    function getDescripcion()
    { return $this->descripcion;}

    function getCapacitador()
    { return $this->capacitador;}

    function getFechaProgramada()
    { return $this->fecha_programada;}

    function getDuracion()
    { return $this->duracion;}

    function getFechaInicio()
    { return $this->fecha_inicio;}

    function getFechaFin()
    { return $this->fecha_fin;}

    function getIdModalidad()
    { return $this->id_modalidad;}

    function getCreatedDate()
    { return $this->created_date;}

    function getIdUser()
    { return $this->id_user;}


    //SETTERS
    function setIdCapacitacion($val)
    { $this->id_capacitacion=$val;}

    function setIdPlanCapacitacion($val)
    { $this->id_plan_capacitacion=$val;}

    function setIdCategoria($val)
    { $this->id_categoria=$val;}

    function setTema($val)
    { $this->tema=$val;}

    function setDescripcion($val)
    { $this->descripcion=$val;}

    function setCapacitador($val)
    {  $this->capacitador=$val;}

    function setFechaProgramada($val)
    {  $this->fecha_programada=$val;}

    function setDuracion($val)
    { $this->duracion=$val;}

    function setFechaInicio($val)
    {  $this->fecha_inicio=$val;}

    function setFechaFin($val)
    {  $this->fecha_fin=$val;}

    function setIdModalidad($val)
    {  $this->id_modalidad=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}

    function setIdUser($val)
    {  $this->id_user=$val;}
    


    public static function getNoConformidades($startDate, $endDate, $id_responsable_ejecucion){
        $stmt=new sQuery();
        $query="select nc.id_no_conformidad, nc.nro_no_conformidad, nc.nombre, nc.sector, nc.tipo, nc.analisis_causa, nc.tipo_accion,
nc.descripcion, nc.accion_inmediata, nc.analisis_causa_desc,
DATE_FORMAT(nc.created_date,  '%d/%m/%Y %H:%i') as created_date,
DATE_FORMAT(nc.fecha_cierre,  '%d/%m/%Y') as fecha_cierre,
nc.id_user, us.user,
concat(em.apellido, ' ', em.nombre) as responsable_seguimiento,
CASE WHEN nc.fecha_cierre is not null THEN 'CERRADA'
     WHEN (select count(*) from nc_accion ax where ax.id_no_conformidad = nc.id_no_conformidad) > 0 THEN 'PENDIENTE'
     ELSE 'ABIERTA'
END as estado,
(select DATE_FORMAT(max(ax.fecha_implementacion), '%d/%m/%Y') from nc_accion ax where ax.id_no_conformidad = nc.id_no_conformidad) as fecha_implementacion
from nc_no_conformidad nc
join empleados em on nc.id_responsable_seguimiento = em.id_empleado
join sec_users us on us.id_user = nc.id_user
where date(nc.created_date) between :startDate and :endDate
and if(:id_responsable_ejecucion is null, 1, exists(select 1
			                                        from nc_accion nax
                                                    where nax.id_no_conformidad = nc.id_no_conformidad
                                                    and nax.id_responsable_ejecucion = nax.id_responsable_ejecucion))";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':startDate', $startDate);
        $stmt->dpBind(':endDate', $endDate);
        $stmt->dpBind(':id_responsable_ejecucion', $id_responsable_ejecucion);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select nc.id_no_conformidad, nc.nro_no_conformidad, nc.nombre, nc.sector, nc.tipo, nc.analisis_causa, nc.tipo_accion, nc.descripcion,
                    nc.accion_inmediata, nc.analisis_causa_desc, nc.id_responsable_seguimiento,
                    DATE_FORMAT(nc.fecha_cierre, '%d/%m/%Y') as fecha_cierre,
                    nc.id_user,
                    DATE_FORMAT(nc.created_date, '%d/%m/%Y') as created_date,
                    CASE WHEN nc.fecha_cierre is not null THEN 'CERRADA'
                         WHEN (select count(*) from nc_accion ax where ax.id_no_conformidad = nc.id_no_conformidad) > 0 THEN 'PENDIENTE'
                         ELSE 'ABIERTA'
                    END as estado
                    from nc_no_conformidad nc
                    where id_no_conformidad = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdNoConformidad($rows[0]['id_no_conformidad']);
            $this->setNroNoConformidad($rows[0]['nro_no_conformidad']);
            $this->setNombre($rows[0]['nombre']);
            $this->setSector($rows[0]['sector']);
            $this->setTipo($rows[0]['tipo']);
            $this->setAnalisisCausa($rows[0]['analisis_causa']);
            $this->setTipoAccion($rows[0]['tipo_accion']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setAccionInmediata($rows[0]['accion_inmediata']);
            $this->setAnalisisCausaDesc($rows[0]['analisis_causa_desc']);
            $this->setIdResponsableSeguimiento($rows[0]['id_responsable_seguimiento']);
            $this->setFechaCierre($rows[0]['fecha_cierre']);
            $this->setCreatedDate($rows[0]['created_date']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setEstado($rows[0]['estado']);
        }
    }



    function save(){ //ok
        if($this->id_no_conformidad)
        {$rta = $this->updateNoConformidad();}
        else
        {$rta =$this->insertNoConformidad();}
        return $rta;
    }

    public function updateNoConformidad(){ //ok

        $stmt=new sQuery();
        $query="update nc_no_conformidad set
                nro_no_conformidad= :nro_no_conformidad,
                nombre= :nombre,
                sector= :sector,
                tipo= :tipo,
                analisis_causa= :analisis_causa,
                tipo_accion= :tipo_accion,
                descripcion= :descripcion,
                accion_inmediata= :accion_inmediata,
                analisis_causa_desc= :analisis_causa_desc,
                id_responsable_seguimiento= :id_responsable_seguimiento,
                fecha_cierre= STR_TO_DATE(:fecha_cierre, '%d/%m/%Y')
                where id_no_conformidad = :id_no_conformidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_no_conformidad', $this->getNroNoConformidad());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':sector', $this->getSector());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':analisis_causa', $this->getAnalisisCausa());
        $stmt->dpBind(':tipo_accion', $this->getTipoAccion());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':accion_inmediata', $this->getAccionInmediata());
        $stmt->dpBind(':analisis_causa_desc', $this->getAnalisisCausaDesc());
        $stmt->dpBind(':id_responsable_seguimiento', $this->getIdResponsableSeguimiento());
        $stmt->dpBind(':fecha_cierre', $this->getFechaCierre());
        $stmt->dpBind(':id_no_conformidad', $this->getIdNoConformidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertNoConformidad(){ //ok

        $stmt=new sQuery();
        $query="insert into nc_no_conformidad(nro_no_conformidad, nombre, sector, tipo, analisis_causa, tipo_accion, descripcion, accion_inmediata, analisis_causa_desc, id_responsable_seguimiento, fecha_cierre, created_date, id_user)
                values(:nro_no_conformidad, :nombre, :sector, :tipo, :analisis_causa, :tipo_accion, :descripcion, :accion_inmediata, :analisis_causa_desc, :id_responsable_seguimiento, STR_TO_DATE(:fecha_cierre, '%d/%m/%Y'), sysdate(), :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_no_conformidad', $this->getNroNoConformidad());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':sector', $this->getSector());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':analisis_causa', $this->getAnalisisCausa());
        $stmt->dpBind(':tipo_accion', $this->getTipoAccion());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':accion_inmediata', $this->getAccionInmediata());
        $stmt->dpBind(':analisis_causa_desc', $this->getAnalisisCausaDesc());
        $stmt->dpBind(':id_responsable_seguimiento', $this->getIdResponsableSeguimiento());
        $stmt->dpBind(':fecha_cierre', $this->getFechaCierre());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteNoConformidad(){ //ok
        $stmt=new sQuery();
        $query="delete from nc_no_conformidad where id_no_conformidad= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdNoConformidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



}

?>