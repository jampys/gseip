<?php

class Busqueda
{
    private $id_busqueda;
    private $nombre;
    private $fecha;
    private $fecha_apertura;
    private $fecha_cierre;
    private $id_puesto;
    private $id_localidad;
    private $id_contrato;
    private $estado;

    // GETTERS
    function getIdBusqueda()
    { return $this->id_busqueda;}

    function getNombre()
    { return $this->nombre;}

    function getFecha()
    { return $this->fecha;}

    function getFechaApertura()
    { return $this->fecha_apertura;}

    function getFechaCierre()
    { return $this->fecha_cierre;}

    function getIdPuesto()
    { return $this->id_puesto;}

    function getIdLocalidad()
    { return $this->id_localidad;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getEstado()
    { return $this->estado;}


    //SETTERS
    function setIdBusqueda($val)
    { $this->id_busqueda=$val;}

    function setNombre($val)
    {  $this->nombre=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setFechaApertura($val)
    { $this->fecha_apertura=$val;}

    function setFechaCierre($val)
    { $this->fecha_cierre=$val;}

    function setIdPuesto($val)
    { $this->id_puesto=$val;}

    function setIdLocalidad($val)
    { $this->id_localidad=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setEstado($val)
    { $this->estado=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_busqueda, nombre,
                    DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                    DATE_FORMAT(fecha_apertura, '%d/%m/%Y') as fecha_apertura,
                    DATE_FORMAT(fecha_cierre, '%d/%m/%Y') as fecha_cierre,
                    id_puesto, id_localidad, id_contrato, estado
                    from sel_busquedas
                    where id_busqueda = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdBusqueda($rows[0]['id_busqueda']);
            $this->setNombre($rows[0]['nombre']);
            $this->setFecha($rows[0]['fecha']);
            $this->setFechaApertura($rows[0]['fecha_apertura']);
            $this->setFechaCierre($rows[0]['fecha_cierre']);
            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setIdLocalidad($rows[0]['id_localidad']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setEstado($rows[0]['estado']);
        }
    }


    public static function getBusquedas($id_puesto, $id_localidad, $id_contrato, $todas) { //ok
        $stmt=new sQuery();
        $query = "select bu.id_busqueda,
                  DATE_FORMAT(bu.fecha,  '%d/%m/%Y') as fecha,
                  bu.nombre,
                  DATE_FORMAT(bu.fecha_apertura,  '%d/%m/%Y') as fecha_apertura,
                  DATE_FORMAT(bu.fecha_cierre,  '%d/%m/%Y') as fecha_cierre,
                  pu.nombre as puesto,
                  loc.ciudad as area,
                  co. nombre as contrato,
                  bu.estado
                  from sel_busquedas bu
                  left join puestos pu on bu.id_puesto = pu.id_puesto
                  left join localidades loc on bu.id_localidad = loc.id_localidad
                  left join contratos co on bu.id_contrato = co.id_contrato
                  where bu.id_puesto =  ifnull(:id_puesto, bu.id_puesto)
                  and bu.id_localidad =  ifnull(:id_localidad, bu.id_localidad)
                  and bu.id_contrato =  ifnull(:id_contrato, bu.id_contrato)";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpBind(':id_localidad', $id_localidad);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getBusquedasActivas() { //ok
        $stmt=new sQuery();
        $query = "select bu.id_busqueda, bu.nombre
                  from sel_busquedas bu";
                  //where bu.fecha_cierre is null or bu.fecha_cierre > date(sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_busqueda)
        {$rta = $this->updateBusqueda();}
        else
        {$rta =$this->insertBusqueda();}
        return $rta;
    }


    public function updateBusqueda(){ //ok
        $stmt=new sQuery();
        $query="update sel_busquedas set nombre =:nombre,
                      fecha_apertura = STR_TO_DATE(:fecha_apertura, '%d/%m/%Y'),
                      fecha_cierre = STR_TO_DATE(:fecha_cierre, '%d/%m/%Y'),
                      id_puesto = :id_puesto,
                      id_localidad = :id_localidad,
                      id_contrato = :id_contrato
                where id_busqueda =:id_busqueda";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_apertura', $this->getFechaApertura());
        $stmt->dpBind(':fecha_cierre', $this->getFechaCierre());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':id_busqueda', $this->getIdBusqueda());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertBusqueda(){ //ok
        $stmt=new sQuery();
        $query="insert into sel_busquedas(fecha, nombre, fecha_apertura, fecha_cierre, id_puesto, id_localidad, id_contrato)
                values(sysdate(), :nombre, STR_TO_DATE(:fecha_apertura, '%d/%m/%Y'), STR_TO_DATE(:fecha_cierre, '%d/%m/%Y'), :id_puesto, :id_localidad, :id_contrato)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':fecha_apertura', $this->getFechaApertura());
        $stmt->dpBind(':fecha_cierre', $this->getFechaCierre());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
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



    public static function uploadsUpload($directory, $name, $id_busqueda){ //ok
        $stmt=new sQuery();
        $query="insert into uploads_busqueda(directory, name, fecha, id_busqueda)
                values(:directory, :name, date(sysdate()), :id_busqueda)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':name', $name);
        $stmt->dpBind(':id_busqueda', $id_busqueda);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsLoad($id_busqueda) { //ok
        $stmt=new sQuery();
        $query = "select id_upload, directory, name, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, id_busqueda
                from uploads_busqueda
                where id_busqueda = :id_busqueda
                order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_busqueda', $id_busqueda);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function uploadsDelete($name){ //ok
        $stmt=new sQuery();
        $query="delete from uploads_busqueda where name =:name";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':name', $name);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkFechaEmision($fecha_emision, $id_empleado, $id_grupo, $id_vencimiento, $id_renovacion) {
        /*Busca la renovacion vigente para el id_empleado y id_vencimiento y se asegura que la proxima fecha_emision
        sea mayor. */
        $stmt=new sQuery();
        $query = "select *
                  from vto_renovacion_p
                  where
                  ( -- renovar: busca renovacion vigente y se asegura que la fecha_emision ingresada sea mayor que la de ésta
                  :id_renovacion is null
                  and (id_empleado = :id_empleado or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and fecha_emision >= STR_TO_DATE(:fecha_emision, '%d/%m/%Y')
                  )
                  OR
                  ( -- editar: busca renovacion anterior y ....
                  :id_renovacion is not null
                  and (id_empleado = :id_empleado or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and fecha_emision >= STR_TO_DATE(:fecha_emision, '%d/%m/%Y')
                  and id_renovacion <> :id_renovacion
                  )
                  order by fecha_emision asc
                  limit 1";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpBind(':fecha_emision', $fecha_emision);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_grupo', $id_grupo);
        $stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }

    public function checkFechaVencimiento($fecha_emision, $fecha_vencimiento, $id_empleado, $id_grupo, $id_vencimiento, $id_renovacion) {
        $stmt=new sQuery();
        $query = "select *
                  from vto_renovacion_p
                  where
                  ( -- renovar: busca renovacion vigente y se asegura que la fecha_vencimiento ingresada sea mayor que la de ésta
                  :id_renovacion is null
                  and (id_empleado = :id_empleado or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and fecha_vencimiento >= STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y')
                  )
                  OR
                  ( -- editar: busca renovacion anterior y ....
                  :id_renovacion is not null
                  and (id_empleado = :id_empleado or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and fecha_vencimiento >= STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y')
                  and id_renovacion <> :id_renovacion
                  )
                  order by fecha_emision asc
                  limit 1";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpBind(':fecha_emision', $fecha_emision);
        $stmt->dpBind(':fecha_vencimiento', $fecha_vencimiento);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_grupo', $id_grupo);
        $stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }




}




?>