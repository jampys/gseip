<?php
class Capacitacion
{
    private $id_capacitacion;
    private $id_plan_capacitacion;
    private $periodo;
    private $id_categoria;
    private $tema;
    private $descripcion;
    private $capacitador;
    private $fecha_programada;
    private $duracion;
    private $fecha_inicio;
    private $fecha_fin;
    private $id_modalidad;
    private $observaciones;
    private $created_date;
    private $id_user;

    // GETTERS
    function getIdCapacitacion()
    { return $this->id_capacitacion;}

    function getIdPlanCapacitacion()
    { return $this->id_plan_capacitacion;}

    function getPeriodo()
    { return $this->periodo;}

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

    function getObservaciones()
    { return $this->observaciones;}

    function getCreatedDate()
    { return $this->created_date;}

    function getIdUser()
    { return $this->id_user;}


    //SETTERS
    function setIdCapacitacion($val)
    { $this->id_capacitacion=$val;}

    function setIdPlanCapacitacion($val)
    { $this->id_plan_capacitacion=$val;}

    function setPeriodo($val)
    { $this->periodo=$val;}

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

    function setObservaciones($val)
    {  $this->observaciones=$val;}

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
cg.nombre as categoria,
u.user,
m.nombre as modalidad
from cap_capacitaciones c
join cap_planes_capacitacion pc on pc.id_plan_capacitacion = c.id_plan_capacitacion
join sec_users u on u.id_user = c.id_user
join cap_categorias cg on cg.id_categoria = c.id_categoria
left join cap_modalidades m on m.id_modalidad = c.id_modalidad";

        $stmt->dpPrepare($query);
        //$stmt->dpBind(':startDate', $startDate);
        //$stmt->dpBind(':endDate', $endDate);
        //$stmt->dpBind(':id_responsable_ejecucion', $id_responsable_ejecucion);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getPeriodos() { //ok
        $stmt=new sQuery();
        $query = "select id_plan_capacitacion, periodo, cerrado
                  from cap_planes_capacitacion
                  order by periodo desc";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select c.id_capacitacion, c.id_plan_capacitacion, c.periodo, c.id_categoria, c.tema, c.descripcion, c.capacitador,
DATE_FORMAT(c.fecha_programada,  '%d/%m/%Y %H:%i') as fecha_programada,
c.duracion,
DATE_FORMAT(c.fecha_inicio,  '%d/%m/%Y') as fecha_inicio,
DATE_FORMAT(c.fecha_fin,  '%d/%m/%Y') as fecha_fin,
c.id_modalidad,
DATE_FORMAT(c.created_date,  '%d/%m/%Y %H:%i') as created_date,
c.id_user, c.observaciones
                    from cap_capacitaciones c
                    where id_capacitacion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdCapacitacion($rows[0]['id_capacitacion']);
            $this->setIdPlanCapacitacion($rows[0]['id_plan_capacitacion']);
            $this->setPeriodo($rows[0]['periodo']);
            $this->setIdCategoria($rows[0]['id_categoria']);
            $this->setTema($rows[0]['tema']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setCapacitador($rows[0]['capacitador']);
            $this->setFechaProgramada($rows[0]['fecha_programada']);
            $this->setDuracion($rows[0]['duracion']);
            $this->setFechaInicio($rows[0]['fecha_inicio']);
            $this->setFechaFin($rows[0]['fecha_fin']);
            $this->setIdModalidad($rows[0]['id_modalidad']);
            $this->setObservaciones($rows[0]['observaciones']);
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

    public function updateCapacitacion(){ //ok

        $stmt=new sQuery();
        $query="update cap_capacitaciones set
                periodo= :periodo,
                id_plan_capacitacion= :id_plan_capacitacion,
                id_categoria= :id_categoria,
                tema= :tema,
                descripcion= :descripcion,
                capacitador= :capacitador,
                fecha_programada= STR_TO_DATE(:fecha_programada, '%d/%m/%Y'),
                duracion= :duracion,
                fecha_inicio= STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'),
                fecha_fin= STR_TO_DATE(:fecha_fin, '%d/%m/%Y'),
                id_modalidad= :id_modalidad,
                observaciones= :observaciones
                where id_capacitacion = :id_capacitacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_plan_capacitacion', $this->getIdPlanCapacitacion());
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':id_categoria', $this->getIdCategoria());
        $stmt->dpBind(':tema', $this->getTema());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':capacitador', $this->getCapacitador());
        $stmt->dpBind(':fecha_programada', $this->getFechaProgramada());
        $stmt->dpBind(':duracion', $this->getDuracion());
        $stmt->dpBind(':fecha_inicio', $this->getFechaInicio());
        $stmt->dpBind(':fecha_fin', $this->getFechaFin());
        $stmt->dpBind(':id_modalidad', $this->getIdModalidad());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_capacitacion', $this->getIdCapacitacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertCapacitacion(){ //ok

        $stmt=new sQuery();
        $query="insert into cap_capacitaciones(id_plan_capacitacion, periodo, id_categoria, tema, descripcion, capacitador, fecha_programada, duracion, fecha_inicio, fecha_fin, id_modalidad, observaciones, created_date, id_user)
                values(:id_plan_capacitacion, :periodo, :id_categoria, :tema, :descripcion, :capacitador, STR_TO_DATE(:fecha_programada, '%d/%m/%Y'), :duracion, STR_TO_DATE(:fecha_inicio, '%d/%m/%Y'), STR_TO_DATE(:fecha_fin, '%d/%m/%Y'), :id_modalidad, :observaciones, sysdate(), :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_plan_capacitacion', $this->getIdPlanCapacitacion());
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':id_categoria', $this->getIdCategoria());
        $stmt->dpBind(':tema', $this->getTema());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':capacitador', $this->getCapacitador());
        $stmt->dpBind(':fecha_programada', $this->getFechaProgramada());
        $stmt->dpBind(':duracion', $this->getDuracion());
        $stmt->dpBind(':fecha_inicio', $this->getFechaInicio());
        $stmt->dpBind(':fecha_fin', $this->getFechaFin());
        $stmt->dpBind(':id_modalidad', $this->getIdModalidad());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteCapacitacion(){ //ok
        $stmt=new sQuery();
        $query="delete from cap_capacitaciones where id_capacitacion= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdCapacitacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



}

?>