<?php
class Capacitacion
{
    private $id_capacitacion;
    private $id_plan_capacitacion;
    private $periodo;
    private $id_categoria;
    private $tema;
    private $descripcion;
    private $mes_programada;
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

    function getMesProgramada()
    { return $this->mes_programada;}

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

    function setMesProgramada($val)
    {  $this->mes_programada=$val;}

    function setObservaciones($val)
    {  $this->observaciones=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}

    function setIdUser($val)
    {  $this->id_user=$val;}



    public static function getCapacitaciones($periodo, $id_categoria, $mes_programada, $id_contrato){ //ok
        $stmt=new sQuery();
        $query="select c.id_capacitacion, c.id_plan_capacitacion, c.id_categoria, c.tema, c.descripcion, c.mes_programada,
                DATE_FORMAT(c.created_date,  '%d/%m/%Y %H:%i') as created_date,
                c.id_user,
                cg.nombre as categoria,
                u.user,
                (select count(*) from cap_capacitacion_empleado ce where ce.id_capacitacion = c.id_capacitacion and ce.id_contrato in ($id_contrato)) as cant_participantes
                from cap_capacitaciones c
                join cap_planes_capacitacion pc on pc.id_plan_capacitacion = c.id_plan_capacitacion
                join sec_users u on u.id_user = c.id_user
                join cap_categorias cg on cg.id_categoria = c.id_categoria
                where c.periodo = ifnull(:periodo, c.periodo)
                and c.id_categoria = ifnull(:id_categoria, c.id_categoria)
                and if(:mes_programada = 1, mes_programada is not null, 1)
                and if(:mes_programada = 0, mes_programada is null, 1)
                and if('$id_contrato' != 'ce.id_contrato' , exists (select 1
			                                  from cap_capacitacion_empleado ce
                                              where ce.id_capacitacion = c.id_capacitacion
                                              and ce.id_contrato in ($id_contrato)), 1)";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpBind(':id_categoria', $id_categoria);
        $stmt->dpBind(':mes_programada', $mes_programada);
        //$stmt->dpBind(':id_contrato', $id_contrato);
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
            $query="select c.id_capacitacion, c.id_plan_capacitacion, c.periodo, c.id_categoria, c.tema, c.descripcion, c.mes_programada,
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
            $this->setMesProgramada($rows[0]['mes_programada']);
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
                mes_programada= :mes_programada,
                id_categoria= :id_categoria,
                tema= :tema,
                descripcion= :descripcion,
                observaciones= :observaciones
                where id_capacitacion = :id_capacitacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':mes_programada', $this->getMesProgramada());
        $stmt->dpBind(':id_categoria', $this->getIdCategoria());
        $stmt->dpBind(':tema', $this->getTema());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':observaciones', $this->getObservaciones());
        $stmt->dpBind(':id_capacitacion', $this->getIdCapacitacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertCapacitacion(){ //ok

        $stmt=new sQuery();
        $query="insert into cap_capacitaciones(id_plan_capacitacion, periodo, mes_programada, id_categoria, tema, descripcion, observaciones, created_date, id_user)
                values(:id_plan_capacitacion, :periodo, :mes_programada, :id_categoria, :tema, :descripcion, :observaciones, sysdate(), :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_plan_capacitacion', $this->getIdPlanCapacitacion());
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':mes_programada', $this->getMesProgramada());
        $stmt->dpBind(':id_categoria', $this->getIdCategoria());
        $stmt->dpBind(':tema', $this->getTema());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
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