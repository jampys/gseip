<?php
include_once("puestosModel.php");
include_once("objetivosModel.php");

class ObjetivoPuesto
{
    private $id_habilidad_puesto;
    private $id_habilidad;
    private $id_puesto;
    private $requerida;
    private $periodo;

    private $puesto;
    private $habilidad;

    // GETTERS
    function getIdHabilidadPuesto()
    { return $this->id_habilidad_puesto;}

    function getIdHabilidad()
    { return $this->id_habilidad;}

    function getIdPuesto()
    { return $this->id_puesto;}

    function getRequerida()
    { return $this->requerida;}

    function getPeriodo()
    { return $this->periodo;}

    function getPuesto()
    { return $this->puesto;}

    function getHabilidad()
    { return $this->habilidad;}


    //SETTERS
    function setIdHabilidadPuesto($val)
    { $this->id_habilidad_puesto=$val;}

    function setIdHabilidad($val)
    { $this->id_habilidad=$val;}

    function setIdPuesto($val)
    { $this->id_puesto=$val;}

    function setRequerida($val)
    { $this->requerida=$val;}

    function setPeriodo($val)
    { $this->periodo=$val;}



    function __construct($nro=0){ //constructor

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select * from habilidad_puesto where id_habilidad_puesto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdHabilidadPuesto($rows[0]['id_habilidad_puesto']);
            $this->setIdHabilidad($rows[0]['id_habilidad']);
            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setRequerida($rows[0]['requerida']);
            $this->setPeriodo($rows[0]['periodo']);

            $this->puesto = new Puesto($rows[0]['id_puesto']);
            $this->habilidad = new Habilidad($rows[0]['id_habilidad']);
        }
    }


    public static function getObjetivoPuesto($id_puesto, $id_objetivo, $periodo) { //ok
        $stmt=new sQuery();
        $query = "select op.id_objetivo_puesto, pu.id_puesto, pu.nombre as puesto,
                      ob.id_objetivo, ob.nombre as objetivo,
                      op.periodo, op.id_contrato, op.valor, op.unidad
                      from objetivo_puesto op, objetivos ob, puestos pu
                      where op.id_puesto = pu.id_puesto
                      and op.id_objetivo = ob.id_objetivo
                      and pu.id_puesto = ifnull(:id_puesto, pu.id_puesto)
                      and ob.id_objetivo = ifnull(:id_objetivo, ob.id_objetivo)
                      and op.periodo = ifnull(:periodo, op.periodo)";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpBind(':id_objetivo', $id_objetivo);
        $stmt->dpBind(':periodo', $periodo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    /*function save(){
        if($this->id_habilidad)
        {$rta = $this->updateHabilidad();}
        else
        {$rta =$this->insertHabilidad();}
        return $rta;
    }*/


    public function updateHabilidadPuesto(){
        $stmt=new sQuery();
        $query="update habilidad_puesto set requerida =:requerida
                where id_habilidad_puesto =:id_habilidad_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad_puesto', $this->getIdHabilidadPuesto());
        $stmt->dpBind(':requerida', $this->getRequerida());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertHabilidadPuesto(){

        $stmt=new sQuery();
        $query = 'CALL sp_insertHabilidadPuesto(
                                                    :id_habilidad,
                                                    :id_puesto,
                                                    :requerida,
                                                    :periodo,
                                                    @flag
                                                  )';

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':requerida', $this->getRequerida());
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : 0;


    }


    function deleteHabilidadPuesto(){
        $stmt=new sQuery();
        $query="delete from habilidad_puesto
                where id_habilidad_puesto =:id_habilidad_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad_puesto', $this->getIdHabilidadPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public static function getPeriodos() { //ok
        $stmt=new sQuery();
        $query = "select periodo
                  from objetivo_puesto
                  group by periodo";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

}

?>