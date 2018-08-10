<?php

class ParteEmpleado
{
    private $id_parte_empleado;
    private $fecha; //fecha de registro en el sistema
    private $id_parte;
    private $id_empleado;
    private $conductor;

    private $hs_manejo;
    private $hs_viaje;
    private $hs_base;
    private $hs_50;
    private $hs_100;
    private $vianda_diaria;
    private $vianda_extra;

    // GETTERS
    function getIdParteEmpleado()
    { return $this->id_parte_empleado;}

    function getFecha()
    { return $this->fecha;}

    function getIdParte()
    { return $this->id_parte;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getConductor()
    { return $this->conductor;}

    function getHsManejo()
    { return $this->hs_manejo;}

    function getHsViaje()
    { return $this->hs_viaje;}

    function getHsBase()
    { return $this->hs_base;}

    function getHs50()
    { return $this->hs_50;}

    function getHs100()
    { return $this->hs_100;}

    function getViandaDiaria()
    { return $this->vianda_diaria;}

    function getViandaExtra()
    { return $this->vianda_extra;}


    //SETTERS
    function setIdParteEmpleado($val)
    { $this->id_parte_empleado=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIdParte($val)
    {  $this->id_parte=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

    function setConductor($val)
    { $this->conductor=$val;}

    function setHsManejo($val)
    { $this->hs_manejo=$val;}

    function setHsViaje($val)
    { $this->hs_viaje=$val;}

    function setHsBase($val)
    { $this->hs_base=$val;}

    function setHs50($val)
    { $this->hs_50=$val;}

    function setHs100($val)
    { $this->hs_100=$val;}

    function setViandaDiaria($val)
    { $this->vianda_diaria=$val;}

    function setViandaExtra($val)
    { $this->vianda_extra=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_parte_empleado,
                      id_parte, id_empleado, conductor,
                      hs_manejo, hs_viaje, hs_base, hs_50, hs_100, vianda_diaria, vianda_extra
                      from nov_parte_empleado
                      where id_parte_empleado = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdParteEmpleado($rows[0]['id_parte_empleado']);
            $this->setIdParte($rows[0]['id_parte']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setConductor($rows[0]['conductor']);

            $this->setHsManejo($rows[0]['hs_manejo']);
            $this->setHsViaje($rows[0]['hs_viaje']);
            $this->setHsBase($rows[0]['hs_base']);
            $this->setHs50($rows[0]['hs_50']);
            $this->setHs100($rows[0]['hs_100']);
            $this->setViandaDiaria($rows[0]['vianda_diaria']);
            $this->setViandaExtra($rows[0]['vianda_extra']);
        }
    }


    public static function getParteEmpleado($id_parte) { //ok
        $stmt=new sQuery();
        $query = "select npe.id_parte_empleado,
                  -- DATE_FORMAT(npe.fecha, '%d/%m/%Y') as fecha,
                  npe.id_parte, npe.id_empleado, npe.conductor,
                  npe.hs_manejo, npe.hs_viaje, npe.hs_base, npe.vianda_diaria, npe.vianda_extra,
                  em.apellido, em.nombre
                  from nov_parte_empleado npe
                  join empleados em on npe.id_empleado = em.id_empleado
                  where npe.id_parte = :id_parte
                  order by em.apellido asc, em.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $id_parte);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_parte_empleado)
        {$rta = $this->updateParteEmpleado();}
        else
        {$rta =$this->insertParteEmpleado();}
        return $rta;
    }


    public function updateParteEmpleado(){ //ok
        $stmt=new sQuery();
        $query="update nov_parte_empleado set id_empleado = :id_empleado, conductor = :conductor
                where id_parte_empleado = :id_parte_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':conductor', $this->getConductor());
        $stmt->dpBind(':id_parte_empleado', $this->getIdParteEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function insertParteEmpleado(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_parte_empleado(fecha, id_parte, id_empleado, conductor)
                values(sysdate(), :id_parte, :id_empleado, :conductor)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $this->getIdParte());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':conductor', $this->getConductor());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    function deleteParteEmpleado(){ //ok
        $stmt=new sQuery();
        $query="delete from nov_parte_empleado where id_parte_empleado = :id_parte_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte_empleado', $this->getIdParteEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkEmpleado($id_parte_empleado, $id_parte) { //No se usa
        $stmt=new sQuery();
        /*$query = "select *
                  from nov_cuadrilla_empleado
                  where id_cuadrilla = :id_cuadrilla
                  and id_empleado = :id_empleado
                  and id_cuadrilla_empleado <> :id_cuadrilla_empleado";*/
        $query = "select 1
                  from nov_parte_empleado
                  where id_parte = :id_parte
                  and conductor = 1
                  and id_parte_empleado <> :id_parte_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_parte', $id_parte);
        $stmt->dpBind(':id_parte_empleado', $id_parte_empleado);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }



}




?>