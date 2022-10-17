<?php

class Subcontratista
{
    private $id_subcontratista;
    private $nombre;
    private $razon_social;
    private $cuit;

    // GETTERS
    function getIdSubcontratista()
    { return $this->id_subcontratista;}

    function getNombre()
    { return $this->nombre;}

    function getRazonSocial()
    { return $this->razon_social;}

    function getCuit()
    { return $this->cuit;}

    // SETTERS
    function setIdSubcontratista($val)
    { $this->id_subcontratista=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setRazonSocial($val)
    {  $this->razon_social=$val;}

    function setCuit($val)
    {  $this->cuit=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select sc.id_subcontratista, sc.nombre,
                      co.id_compania, co.razon_social,
                      DATE_FORMAT(fecha_alta, '%d/%m/%Y') as fecha_alta,
                      DATE_FORMAT(fecha_baja, '%d/%m/%Y') as fecha_baja
                      from subcontratistas sc
                      join companias co on co.id_compania = sc.id_compania
                      where id_subcontratista = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdSubcontratista($rows[0]['id_subcontratista']);
            $this->setNombre($rows[0]['nombre']);
            $this->setRazonSocial($rows[0]['razon_social']);

        }
    }


    public static function getSubcontratistas() {
        $stmt=new sQuery();
        $stmt->dpPrepare("select *
                          from subcontratistas su
                          join companias co on su.id_compania = co.id_compania
                          where su.fecha_baja is null
                          order by razon_social asc");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todos los subcontratistas
    }



}


?>