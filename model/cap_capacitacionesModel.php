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



    public static function getCapacitaciones($startDate, $endDate, $id_responsable_ejecucion){ //ok
        $stmt=new sQuery();
        $query="select c.id_capacitacion, c.id_plan_capacitacion, c.id_categoria, c.tema, c.descripcion, c.capacitador,
DATE_FORMAT(c.fecha_programada,  '%d/%m/%Y %H:%i') as fecha_programada,
c.duracion,
DATE_FORMAT(c.fecha_inicio,  '%d/%m/%Y %H:%i') as fecha_inicio,
DATE_FORMAT(c.fecha_fin,  '%d/%m/%Y %H:%i') as fecha_fin,
c.id_modalidad,
DATE_FORMAT(c.created_date,  '%d/%m/%Y %H:%i') as created_date,
c.id_user,
cg.nombre as categoria
from cap_capacitaciones c
join cap_planes_capacitacion pc on pc.id_plan_capacitacion = c.id_plan_capacitacion
join sec_users u on u.id_user = c.id_user
join cap_categorias cg on cg.id_categoria = c.id_categoria";

        $stmt->dpPrepare($query);
        //$stmt->dpBind(':startDate', $startDate);
        //$stmt->dpBind(':endDate', $endDate);
        //$stmt->dpBind(':id_responsable_ejecucion', $id_responsable_ejecucion);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select c.id_capacitacion, c.id_plan_capacitacion, c.id_categoria, c.tema, c.descripcion, c.capacitador,
DATE_FORMAT(c.fecha_programada,  '%d/%m/%Y %H:%i') as fecha_programada,
c.duracion,
DATE_FORMAT(c.fecha_inicio,  '%d/%m/%Y %H:%i') as fecha_inicio,
DATE_FORMAT(c.fecha_fin,  '%d/%m/%Y %H:%i') as fecha_fin,
c.id_modalidad,
DATE_FORMAT(c.created_date,  '%d/%m/%Y %H:%i') as created_date,
c.id_user
                    from cap_capacitaciones c
                    where id_capacitacion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdCapacitacion($rows[0]['id_capacitacion']);
            $this->setIdPlanCapacitacion($rows[0]['id_plan_capacitacion']);
            $this->setIdCategoria($rows[0]['id_categoria']);
            $this->setTema($rows[0]['tema']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setCapacitador($rows[0]['capacitador']);
            $this->setFechaProgramada($rows[0]['fecha_programada']);
            $this->setDuracion($rows[0]['duracion']);
            $this->setFechaInicio($rows[0]['fecha_inicio']);
            $this->setFechaFin($rows[0]['fecha_fin']);
            $this->setIdModalidad($rows[0]['id_modalidad']);
            $this->setCreatedDate($rows[0]['created_date']);
            $this->setIdUser($rows[0]['id_user']);
        }
    }



    function save(){ //ok
        if($this->id_capacitacion)
        {$rta = $this->updateCapacitacion();}
        else
        {$rta =$this->insertCapacitacion();}
        return $rta;
    }

    public function updateCapacitacion(){

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

    private function insertCapacitacion(){

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

    function deleteCapacitacion(){ //ok
        $stmt=new sQuery();
        $query="delete from cap_capacitacion where id_capacitacion= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdCapacitacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



}

?>