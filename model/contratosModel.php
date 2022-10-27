<?php
include_once("empleadosModel.php");

class Contrato
{
    private $id_contrato;
    private $nro_contrato;
    private $nombre;
    private $fecha_desde;
    private $fecha_hasta;
    private $id_responsable;
    private $id_compania;
    private $responsable;
    private $nov_template;

    private $domain;


    //GETTERS
    function getIdContrato()
    { return $this->id_contrato;}

    function getNroContrato()
    { return $this->nro_contrato;}

    function getNombre()
    { return $this->nombre;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getIdResponsable()
    { return $this->id_responsable;}

    function getIdCompania()
    { return $this->id_compania;}

    function getResponsable(){
        return ($this->responsable)? $this->responsable : new Empleado() ;
    }

    function getNovTemplate(){
        return $this->nov_template;
    }

    function getDomain()
    { return explode(',',$this->domain);//$this->domain;
    }


    //SETTERS
    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setNroContrato($val)
    { $this->nro_contrato=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setFechaDesde($val)
    { $this->fecha_desde=$val;}

    function setFechaHasta($val)
    { $this->fecha_hasta=$val;}

    function setIdResponsable($val)
    { $this->id_responsable=$val;}

    function setIdCompania($val)
    { $this->id_compania=$val;}

    function setNovTemplate($val)
    { $this->nov_template=$val;}

    function setDomain($val)
    { $this->domain=$val;}



    function Contrato($id_contrato = 0){ //constructor ok

        if ($id_contrato!= 0){

            $stmt=new sQuery();
            $query="select co.id_contrato, co.nro_contrato,
                    DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                    re.apellido, re.nombre, cia.razon_social,
                    co.id_responsable, co.id_compania, co.nombre, co.id_domain,
                    co.nov_template
                    from contratos co, empleados re, companias cia
                    where co.id_responsable = re.id_empleado
                    and co.id_compania = cia.id_compania
                    and co.id_contrato = :id_contrato";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':id_contrato', $id_contrato);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setNroContrato($rows[0]['nro_contrato']);
            $this->setNombre($rows[0]['nombre']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setIdResponsable($rows[0]['id_responsable']);
            $this->setIdCompania($rows[0]['id_compania']);
            $this->setNovTemplate($rows[0]['nov_template']);

            $this->responsable = new Empleado($rows[0]['id_responsable']);
            $this->setDomain($rows[0]['id_domain']);

        }
    }


    public static function getContratos() { //ok
        $stmt=new sQuery();
        $query = "select co.id_contrato, co.nro_contrato, co.nombre,
                  DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  CONCAT(re.apellido, ' ', re.nombre) as responsable,
                  if(co.fecha_hasta < sysdate(), 'INACTIVO', 'ACTIVO')as estado,
                  cia.nombre as compania, co.id_domain
                  from contratos co, empleados re, companias cia
                  where co.id_responsable = re.id_empleado
                  and co.id_compania = cia.id_compania";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function getContratosControl() { //ok
        /* contratos sobre los que el usuario tiene control TOTAL
         * solo los contratos activos y sobre los que tiene control el usuario*/
        $stmt=new sQuery();
        $query = "select co.id_contrato, co.nro_contrato, co.nombre,
                  DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  co.id_domain
                  from v_sec_contratos_control co
                  where (co.fecha_hasta is null or co.fecha_hasta >= sysdate()  )
                  order by co.nombre";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function getContratosControlNovedades() { //ok
        /* contratos sobre los que el usuario tiene control sobre las Novedades
         * solo los contratos activos y sobre los que tiene control el usuario*/
        $stmt=new sQuery();
        $query = "select co.id_contrato, co.nro_contrato, co.nombre,
                  DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  co.id_domain
                  from v_sec_contratos_control_nov co
                  where (co.fecha_hasta is null or co.fecha_hasta >= sysdate()  )
                  order by co.nombre";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getContratosControlVencimientos() { //ok
        /* contratos sobre los que el usuario tiene control sobre los Vencimientos
         * solo los contratos activos y sobre los que tiene control el usuario*/
        $stmt=new sQuery();
        $query = "select co.id_contrato, co.nro_contrato, co.nombre,
                  DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  co.id_domain
                  from v_sec_contratos_control_venc co
                  where (co.fecha_hasta is null or co.fecha_hasta >= sysdate()  )
                  order by co.nombre";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_contrato)
        {$rta = $this->updateContrato();}
        else
        {$rta =$this->insertContrato();}
        return $rta;
    }

    public function updateContrato(){ //ok

        $stmt=new sQuery();
        $query="update contratos set
                nro_contrato = :nro_contrato,
                nombre = :nombre,
                fecha_desde = STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                fecha_hasta = STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                id_responsable= :id_responsable,
                id_compania= :id_compania
                where id_contrato = :id_contrato";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_contrato', $this->getNroContrato());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_responsable', $this->getIdResponsable());
        $stmt->dpBind(':id_compania', $this->getIdCompania());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertContrato(){ //ok

        $stmt=new sQuery();
        $query="insert into contratos(nro_contrato, nombre, fecha_desde, fecha_hasta, id_responsable, id_compania)
                values(:nro_contrato,
                       :nombre,
                        STR_TO_DATE(:fecha_desde, '%d/%m/%Y'),
                        STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'),
                        :id_responsable,
                        :id_compania
                      )";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nro_contrato', $this->getNroContrato());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_desde', $this->getFechaDesde());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_responsable', $this->getIdResponsable());
        $stmt->dpBind(':id_compania', $this->getIdCompania());

        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }



}

?>