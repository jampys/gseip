<?php
include_once("puestosModel.php");
include_once("objetivosModel.php");

class ObjetivoPuesto
{
    private $id_objetivo_puesto;
    private $id_objetivo;
    private $id_puesto;
    private $periodo;
    private $id_contrato;
    private $valor;

    private $puesto;
    private $objetivo;

    // GETTERS
    function getIdObjetivoPuesto()
    { return $this->id_objetivo_puesto;}

    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getIdPuesto()
    { return $this->id_puesto;}

    function getPeriodo()
    { return $this->periodo;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getValor()
    { return $this->valor;}


    function getPuesto()
    { return $this->puesto;}

    function getObjetivo()
    { return $this->objetivo;}


    //SETTERS
    function setIdObjetivoPuesto($val)
    { $this->id_objetivo_puesto=$val;}

    function setIdObjetivo($val)
    { $this->id_objetivo=$val;}

    function setIdPuesto($val)
    { $this->id_puesto=$val;}

    function setPeriodo($val)
    { $this->periodo=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setValor($val)
    { $this->valor=$val;}



    function __construct($nro=0){ //constructor ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select * from objetivo_puesto where id_objetivo_puesto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdObjetivoPuesto($rows[0]['id_objetivo_puesto']);
            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setPeriodo($rows[0]['periodo']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setValor($rows[0]['valor']);

            $this->puesto = new Puesto($rows[0]['id_puesto']);
            $this->objetivo = new Objetivo($rows[0]['id_objetivo']);
        }
    }


    public static function getObjetivoPuesto($id_puesto, $id_objetivo, $periodo) { //ok
        $stmt=new sQuery();
        $query = "select op.id_objetivo_puesto, pu.id_puesto,
                      pu.nombre as puesto,
                      ob.id_objetivo,
                      ob.nombre as objetivo,
                      op.periodo, op.id_contrato, op.valor, op.unidad,
                      cia.razon_social as compania
                      from objetivo_puesto op
                      join objetivos ob on op.id_objetivo = ob.id_objetivo
                      join puestos pu on op.id_puesto = pu.id_puesto
                      left join contratos co on op.id_contrato = co.id_contrato
                      left join companias cia on co.id_compania = cia.id_compania
                      where pu.id_puesto = ifnull(:id_puesto, pu.id_puesto)
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


    public function insertObjetivoPuesto(){ //ok

        $stmt=new sQuery();
        $query = 'CALL sp_insertObjetivoPuesto(
                                                    :id_objetivo,
                                                    :id_puesto,
                                                    :periodo,
                                                    :id_contrato,
                                                    :valor,
                                                    @flag
                                                  )';

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':periodo', $this->getPeriodo());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':valor', $this->getValor());
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