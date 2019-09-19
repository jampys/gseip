<?php

class Rol
{
    private $id_role;
    private $nombre;
    private $descripcion;

    // GETTERS
    function getIdRole()
    { return $this->id_role;}

    function getNombre()
    { return $this->nombre;}

    function getDescripcion()
    { return $this->descripcion;}


    // SETTERS
    function setIdRole($val)
    { $this->id_role=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setDescripcion($val)
    {  $this->descripcion=$val;}



    public static function getRoles() { //ok
        $stmt=new sQuery();
        $stmt->dpPrepare("select *
                          from sec_roles");
        $stmt->dpExecute();
        return $stmt->dpFetchAll(); // retorna todas las localidades
    }

    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select *
                    from sec_roles
                    where id_role = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdRole($rows[0]['id_role']);
            $this->setNombre($rows[0]['nombre']);
            $this->setDescripcion($rows[0]['descripcion']);
        }
    }



}


?>