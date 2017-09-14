<?php


class Objetivo
{
    private $id_objetivo;
    private $nombre;
    private $tipo;
    private $objetivo_superior;

    // GETTERS
    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getNombre()
    { return $this->nombre;}

    function getTipo()
    { return $this->tipo;}

    function getObjetivoSuperior()
    { return $this->objetivo_superior;}


    //SETTERS
    function setIdObjetivo($val)
    { $this->id_objetivo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setTipo($val)
    { $this->tipo=$val;}

    function setObjetivoSuperior($val)
    {  $this->objetivo_superior=$val;}


    public static function getObjetivos() { //ok
        $stmt=new sQuery();
        $query="select ob.id_objetivo, ob.nombre, ob.tipo, su.nombre as objetivo_superior
                    from objetivos ob
                    left join objetivos su on ob.objetivo_superior = su.id_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from objetivos where id_objetivo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setNombre($rows[0]['nombre']);
            $this->setTipo($rows[0]['tipo']);
            $this->setObjetivoSuperior($rows[0]['objetivo_superior']);
        }
    }



    function save(){ //ok
        if($this->id_objetivo)
        {$rta = $this->updateObjetivo();}
        else
        {$rta =$this->insertObjetivo();}
        return $rta;
    }

    public function updateObjetivo(){ //ok

        $stmt=new sQuery();
        $query="update objetivos set
                nombre= :nombre,
                tipo= :tipo,
                objetivo_superior= :objetivo_superior
                where id_objetivo = :id_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':objetivo_superior', $this->getObjetivoSuperior());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertObjetivo(){ //ok

        $stmt=new sQuery();
        $query="insert into objetivos(nombre, tipo, objetivo_superior)
                values(:nombre, :tipo, :objetivo_superior)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':objetivo_superior', $this->getObjetivoSuperior());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteObjetivo(){ //ok
        $stmt=new sQuery();
        $query="delete from objetivos where id_objetivo= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdObjetivo());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function autocompletarObjetivos($term) { //ok
        $stmt=new sQuery();
        /*$query = "select *
                  from objetivos
                  where nombre like '%$term%'";*/
        $query = "select id_objetivo_puesto, op.id_objetivo, ob.nombre, id_contrato, periodo, op.valor
from objetivo_puesto op, objetivos ob
where op.id_objetivo = ob.id_objetivo
and nombre like '%$term%'
UNION
select null, id_objetivo, nombre, null, null, null
from objetivos
where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

}



?>