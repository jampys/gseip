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



    function save(){
        if($this->id_puesto)
        {$rta = $this->updatePuesto();}
        else
        {$rta =$this->insertPuesto();}
        return $rta;
    }

    public function updatePuesto(){

        $stmt=new sQuery();
        $query="update puestos set
                nombre= :nombre,
                descripcion= :descripcion,
                codigo= :codigo,
                codigo_superior= :codigo_superior
                where id_puesto = :id_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':codigo_superior', $this->getCodigoSuperior());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertPuesto(){

        $stmt=new sQuery();
        $query="insert into puestos(nombre, descripcion, codigo, codigo_superior)
                values(:nombre, :descripcion, :codigo, :codigo_superior)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':codigo_superior', $this->getCodigoSuperior());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deletePuesto(){
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function autocompletarPuestos($term) {
        $stmt=new sQuery();
        $query = "select *
                  from puestos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

}



?>