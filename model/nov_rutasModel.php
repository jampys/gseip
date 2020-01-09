<?php
//include_once("empleadosModel.php");

class Ruta
{
    private $id_ruta;
    private $id_contrato;
    private $id_convenio;
    private $nombre;

    //GETTERS
    function getIdRuta()
    { return $this->id_ruta;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getIdConvenio()
    { return $this->id_convenio;}

    function getNombre()
    { return $this->nombre;}


    //SETTERS
    function setIdRuta($val)
    { $this->id_ruta=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setIdConvenio($val)
    { $this->id_convenio=$val;}

    function setNombre($val)
    { $this->nombre=$val;}



    function Ruta($id_ruta = 0){ //constructor ok

        if ($id_ruta!= 0){

            $stmt=new sQuery();
            $query="select ru.id_ruta, ru.id_contrato, ru.id_convenio, ru.nombre
                    from nov_rutas ru
                    where ru.id_ruta = :id_ruta";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':id_ruta', $id_ruta);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdRuta($rows[0]['id_ruta']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setIdConvenio($rows[0]['id_convenio']);
            $this->setNombre($rows[0]['nombre']);
        }
    }


    public static function getContratos() { //ok
        $stmt=new sQuery();
        $query = "select co.id_contrato, co.nro_contrato, co.nombre,
                  DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  CONCAT(re.apellido, ' ', re.nombre) as responsable,
                  cia.nombre as compania, co.id_domain
                  from contratos co, empleados re, companias cia
                  where co.id_responsable = re.id_empleado
                  and co.id_compania = cia.id_compania";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function getContratosControl() { //ok
        //solo los contratos activos y sobre los que tiene control el usuario
        $stmt=new sQuery();
        $query = "select co.id_contrato, co.nro_contrato, co.nombre,
                  DATE_FORMAT(co.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(co.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                  CONCAT(re.apellido, ' ', re.nombre) as responsable,
                  cia.nombre as compania, co.id_domain
                  from v_sec_contratos_control co, empleados re, companias cia
                  where co.id_responsable = re.id_empleado
                  and co.id_compania = cia.id_compania
                  and (co.fecha_hasta is null or co.fecha_hasta >= sysdate()  ) ";
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