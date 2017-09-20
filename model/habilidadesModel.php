<?php


class Habilidad
{
    private $id_habilidad;
    private $codigo;
    private $nombre;


    // GETTERS
    function getIdHabilidad()
    { return $this->id_habilidad;}

    function getCodigo()
    { return $this->codigo;}

    function getNombre()
    { return $this->nombre;}


    //SETTERS
    function setIdHabilidad($val)
    { $this->id_habilidad=$val;}

    function setCodigo($val)
    {  $this->codigo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}



    function __construct($nro=0){ //constructor

        if ($nro!=0){
            $stmt=new sQuery();
            $query="select * from habilidades where id_habilidad = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdHabilidad($rows[0]['id_habilidad']);
            $this->setCodigo($rows[0]['codigo']);
            $this->setNombre($rows[0]['nombre']);
        }
    }


    public static function getHabilidades() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from habilidades");
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){
        if($this->id_habilidad)
        {$rta = $this->updateHabilidad();}
        else
        {$rta =$this->insertHabilidad();}
        return $rta;
    }


    public function updateHabilidad(){
        $stmt=new sQuery();
        $query="update habilidades set codigo =:codigo, nombre =:nombre
                where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertHabilidad(){
        $stmt=new sQuery();
        $query="insert into habilidades(codigo, nombre)
                values(:codigo, :nombre)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteHabilidad(){
        $stmt=new sQuery();
        $query="delete from habilidades where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function autocompletarHabilidades($term) {
        $stmt=new sQuery();
        $query = "select *
                  from habilidades
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


}


?>