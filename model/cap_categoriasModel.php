<?php

class Categoria
{
    private $id_categoria;
    private $nombre;

    // GETTERS
    function getIdCategoria()
    { return $this->id_categoria;}

    function getNombre()
    { return $this->nombre;}


    // SETTERS
    function setIdCategoria($val)
    { $this->id_categoria=$val;}

    function setNombre($val)
    { $this->nombre=$val;}


    public static function getCategorias() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select * from cap_categorias");
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}


?>