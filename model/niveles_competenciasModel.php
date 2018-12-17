<?php

class NivelCompetencia
{
    private $id_nivel_competencia;
    private $nombre;

    // GETTERS
    function getIdNivelCompetencia()
    { return $this->id_nivel_competencia;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdNivelCompetencia($val)
    { $this->id_nivel_competencia=$val;}


    function setNombre($val)
    { $this->nombre=$val;}


    public static function getNivelesCompetencias() { //ok
        $stmt=new sQuery();
        $query="select *
        from competencias_niveles";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from competencias_niveles
                    where id_nivel_competencia = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setNombre($rows[0]['nombre']);
            $this->setIdNivelCompetencia($rows[0]['id_nivel_competencia']);
        }
    }



}


?>