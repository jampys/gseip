<?php
class NoConformidad
{
    private $id_no_conformidad;
    private $nro_no_conformidad;
    private $nombre;
    private $sector;
    private $tipo;
    private $analisis_causa;
    private $tipo_accion;
    private $descripcion;
    private $accion_inmediata;
    private $analisis_causa_desc;
    private $id_responsable_seguimiento;
    private $fecha_cierre;
    private $created_date;
    private $id_user;

    private $estado;

    // GETTERS
    function getIdNoConformidad()
    { return $this->id_no_conformidad;}

    function getNroNoConformidad()
    { return $this->nro_no_conformidad;}

    function getNombre()
    { return $this->nombre;}

    function getSector()
    { return $this->sector;}

    function getTipo()
    { return $this->tipo;}

    function getAnalisisCausa()
    { return $this->analisis_causa;}

    function getTipoAccion()
    { return $this->tipo_accion;}

    function getDescripcion()
    { return $this->descripcion;}

    function getAccionInmediata()
    { return $this->accion_inmediata;}

    function getAnalisisCausaDesc()
    { return $this->analisis_causa_desc;}

    function getIdResponsableSeguimiento()
    { return $this->id_responsable_seguimiento;}

    function getFechaCierre()
    { return $this->fecha_cierre;}

    function getCreatedDate()
    { return $this->created_date;}

    function getIdUser()
    { return $this->id_user;}

    function getEstado()
    { return $this->estado;}



    //SETTERS
    function setIdNoConformidad($val)
    { $this->id_no_conformidad=$val;}

    function setNroNoConformidad($val)
    { $this->nro_no_conformidad=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setSector($val)
    { $this->sector=$val;}

    function setTipo($val)
    { $this->tipo=$val;}

    function setAnalisisCausa($val)
    {  $this->analisis_causa=$val;}

    function setTipoAccion($val)
    {  $this->tipo_accion=$val;}

    function setDescripcion($val)
    { $this->descripcion=$val;}

    function setAccionInmediata($val)
    {  $this->accion_inmediata=$val;}

    function setAnalisisCausaDesc($val)
    {  $this->analisis_causa_desc=$val;}

    function setIdResponsableSeguimiento($val)
    {  $this->id_responsable_seguimiento=$val;}

    function setFechaCierre($val)
    {  $this->fecha_cierre=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}

    function setIdUser($val)
    {  $this->id_user=$val;}

    function setEstado($val)
    {  $this->estado=$val;}


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