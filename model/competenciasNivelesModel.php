<?php

class CompetenciasNiveles
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


    public static function getNivelesCompetencias() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from competencias_niveles");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los niveles de competencias
    }



}


?>