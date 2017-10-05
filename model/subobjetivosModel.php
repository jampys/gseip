<?php
class Subobjetivo
{
    private $id_objetivo_sub;
    private $nombre;
    private $id_objetivo;
    private $id_area;

    //GETTERS
    function getIdObjetivoSub()
    { return $this->id_objetivo_sub;}

    function getNombre()
    { return $this->nombre;}

    function getIdObjetivo()
    { return $this->id_objetivo;}

    function getIdArea()
    { return $this->id_area;}


    //SETTERS
    function setIdObjetivoSub($val)
    { $this->id_objetivo_sub=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setIdObjetivo($val)
    {  $this->id_objetivo=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}



    function __construct($nro=0){ //constructor ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select *
                    from objetivos_sub
                    where id_objetivo_sub = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdObjetivoSub($rows[0]['id_objetivo_sub']);
            $this->setNombre($rows[0]['nombre']);
            $this->setIdObjetivo($rows[0]['id_objetivo']);
            $this->setIdArea($rows[0]['id_area']);
        }
    }

    //Devuelve todos los subobjetivos de un determinado objetivo
    public static function getSubobjetivos($id_objetivo) { //ok
        $stmt=new sQuery();
        $query = "select os.*, ar.nombre as area
                  from objetivos_sub os, areas ar
                  where os.id_area = ar.id_area
                  and id_objetivo = :id_objetivo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo', $id_objetivo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    public function updateSubobjetivo(){ //ok

        $stmt=new sQuery();
        $query="update objetivos_sub
                set nombre= :nombre,
                id_objetivo= :id_objetivo,
                id_area= :id_area
                where id_objetivo_sub = :id_objetivo_sub";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_objetivo_sub', $this->getIdObjetivoSub());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    public function insertSubobjetivo(){ //ok

        $stmt=new sQuery();
        $query="insert into objetivos_sub(nombre, id_objetivo, id_area)
                values(:nombre, :id_objetivo, :id_area)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':id_objetivo', $this->getIdObjetivo());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public function deleteSubobjetivo(){ //ok
        $stmt=new sQuery();
        $query="delete from objetivos_sub where id_objetivo_sub= :id_objetivo_sub";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_objetivo_sub', $this->getIdObjetivoSub());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

}

?>