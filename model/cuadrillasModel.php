<?php
include_once("model/cuadrilla-empleadoModel.php");

class Cuadrilla
{
    private $id_cuadrilla;
    private $id_contrato;
    private $default_id_vehiculo;
    private $default_id_area;
    private $nombre;
    private $actividad;

    private $conductores = array();
    private $acompañantes = array();

    // GETTERS
    function getIdCuadrilla()
    { return $this->id_cuadrilla;}

    function getIdContrato()
    { return $this->id_contrato;}

    function getDefaultIdVehiculo()
    { return $this->default_id_vehiculo;}

    function getDefaultIdArea()
    { return $this->default_id_area;}

    function getNombre()
    { return $this->nombre;}

    function getActividad()
    { return $this->actividad;}

    function getConductores()
    { return $this->conductores;}

    function getAcompañantes()
    { return $this->acompañantes;}


    //SETTERS
    function setIdCuadrilla($val)
    {  $this->id_cuadrilla=$val;}

    function setIdContrato($val)
    { $this->id_contrato=$val;}

    function setDefaultIdVehiculo($val)
    { $this->default_id_vehiculo=$val;}

    function setDefaultIdArea($val)
    { $this->default_id_area=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setActividad($val)
    { $this->actividad=$val;}

    function setConductores($val)
    { $this->conductores=$val;}

    function setAcompañantes($val)
    { $this->acompañantes=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_cuadrilla, id_contrato, default_id_vehiculo,
                      default_id_area, nombre, actividad
                      from nov_cuadrillas
                      where id_cuadrilla = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdCuadrilla($rows[0]['id_cuadrilla']);
            $this->setIdContrato($rows[0]['id_contrato']);
            $this->setDefaultIdVehiculo($rows[0]['default_id_vehiculo']);
            $this->setDefaultIdArea($rows[0]['default_id_area']);
            $this->setNombre($rows[0]['nombre']);
            $this->setActividad($rows[0]['actividad']);

            $this->conductores = CuadrillaEmpleado::getCuadrillaEmpleado($this->getIdCuadrilla(), 1);
            $this->acompañantes = CuadrillaEmpleado::getCuadrillaEmpleado($this->getIdCuadrilla(), 0);
        }
    }


    public static function getCuadrillas($id_contrato, $todas) {
        //trae las cuadrillas para la grilla de cuadrillas
        $stmt=new sQuery();
        $query = "select
                  (select nce.id_empleado from nov_cuadrilla_empleado nce, nov_cuadrillas nc
                   where nce.id_cuadrilla = nc.id_cuadrilla and nc.id_contrato = :id_contrato and nc.id_cuadrilla = cu.id_cuadrilla limit 1) as empleado_1,
                   (select nce.id_empleado from nov_cuadrilla_empleado nce, nov_cuadrillas nc
                   where nce.id_cuadrilla = nc.id_cuadrilla and nc.id_contrato = :id_contrato and nc.id_cuadrilla = cu.id_cuadrilla limit 1, 1) as empleado_2,
                  cu.id_cuadrilla, cu.id_contrato, cu.default_id_vehiculo, cu.default_id_area, cu.nombre, cu.actividad,
                  co.nombre as contrato,
                  concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                  concat(ar.codigo, ' ', ar.nombre) as area
                  from nov_cuadrillas cu
                  join contratos co on cu.id_contrato = co.id_contrato
                  join vto_vehiculos ve on cu.default_id_vehiculo = ve.id_vehiculo
                  join nov_areas ar on cu.default_id_area = ar.id_area
                  where cu.id_contrato =  ifnull(:id_contrato, cu.id_contrato)
                  order by cu.nombre";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getCuadrillasForPartes($id_contrato, $fecha_parte) {
        //trae las cuadrillas para el formulario de insert
        /*$stmt=new sQuery();
        $query = "select
                  (select nce.id_empleado from nov_cuadrilla_empleado nce, nov_cuadrillas nc
                   where nce.id_cuadrilla = nc.id_cuadrilla and nc.id_contrato = :id_contrato and nc.id_cuadrilla = cu.id_cuadrilla limit 1) as empleado_1,
                   (select nce.id_empleado from nov_cuadrilla_empleado nce, nov_cuadrillas nc
                   where nce.id_cuadrilla = nc.id_cuadrilla and nc.id_contrato = :id_contrato and nc.id_cuadrilla = cu.id_cuadrilla limit 1, 1) as empleado_2,
                  cu.id_cuadrilla, cu.id_contrato, cu.default_id_vehiculo, cu.default_id_area, cu.nombre, cu.actividad,
                  co.nombre as contrato,
                  concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                  concat(ar.codigo, ' ', ar.nombre) as area
                  from nov_cuadrillas cu
                  join contratos co on cu.id_contrato = co.id_contrato
                  join vto_vehiculos ve on cu.default_id_vehiculo = ve.id_vehiculo
                  join nov_areas ar on cu.default_id_area = ar.id_area
                  where cu.id_contrato =  ifnull(:id_contrato, cu.id_contrato)
                  and not exists( select 1
                                  from nov_partes pax
                                  where pax.id_contrato = cu.id_contrato
                                  and pax.cuadrilla = cu.nombre
                                  and pax.fecha_parte = STR_TO_DATE(:fecha_parte, '%d/%m/%Y')
                                )
                  order by cu.nombre";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':fecha_parte', $fecha_parte);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();*/

        $detalle = array();
        $stmt=new sQuery();
        $query = "select
                  (select nce.id_empleado from nov_cuadrilla_empleado nce, nov_cuadrillas nc
                   where nce.id_cuadrilla = nc.id_cuadrilla and nc.id_contrato = :id_contrato and nc.id_cuadrilla = cu.id_cuadrilla limit 1) as empleado_1,
                   (select nce.id_empleado from nov_cuadrilla_empleado nce, nov_cuadrillas nc
                   where nce.id_cuadrilla = nc.id_cuadrilla and nc.id_contrato = :id_contrato and nc.id_cuadrilla = cu.id_cuadrilla limit 1, 1) as empleado_2,
                  cu.id_cuadrilla, cu.id_contrato, cu.default_id_vehiculo, cu.default_id_area, cu.nombre, cu.actividad,
                  co.nombre as contrato,
                  concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                  concat(ar.codigo, ' ', ar.nombre) as area
                  from nov_cuadrillas cu
                  join contratos co on cu.id_contrato = co.id_contrato
                  join vto_vehiculos ve on cu.default_id_vehiculo = ve.id_vehiculo
                  join nov_areas ar on cu.default_id_area = ar.id_area
                  where cu.id_contrato =  ifnull(:id_contrato, cu.id_contrato)
                  and not exists( select 1
                                  from nov_partes pax
                                  where pax.id_contrato = cu.id_contrato
                                  and pax.cuadrilla = cu.nombre
                                  and pax.fecha_parte = STR_TO_DATE(:fecha_parte, '%d/%m/%Y')
                                )
                  order by cu.nombre";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':fecha_parte', $fecha_parte);
        $stmt->dpExecute();
        //return $stmt->dpFetchAll();
        $rows = $stmt->dpFetchAll();


        //Se debe formatear de esta manera para poder enviar un array de objetos a javascript
        foreach($rows as $row){
            $unaCuadrilla = new Cuadrilla($row['id_cuadrilla']);
            $detalle[] = array( 'id_cuadrilla'=>$row['id_cuadrilla'],
                'id_contrato'=>$row['id_contrato'],
                'default_id_vehiculo'=>$row['default_id_vehiculo'],
                'default_id_area'=>$row['default_id_area'],
                'nombre'=>$row['nombre'],
                'actividad'=>$row['actividad'],
                'contrato'=>$row['contrato'],
                'vehiculo'=>$row['vehiculo'],
                'area'=>$row['area'],
                'acompanantes'=>$unaCuadrilla->getAcompanantes()
            );
        }

        return $detalle;


    }


    function save(){ //ok
        if($this->id_cuadrilla)
        {$rta = $this->updateCuadrilla();}
        else
        {$rta =$this->insertCuadrilla();}
        return $rta;
    }


    public function updateCuadrilla(){ //ok
        $stmt=new sQuery();
        $query="update nov_cuadrillas set id_contrato =:id_contrato,
                      default_id_vehiculo = :default_id_vehiculo,
                      default_id_area = :default_id_area,
                      nombre = :nombre,
                      actividad = :actividad
                where id_cuadrilla =:id_cuadrilla";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':default_id_vehiculo', $this->getDefaultIdVehiculo());
        $stmt->dpBind(':default_id_area', $this->getDefaultIdArea());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':actividad', $this->getActividad());
        $stmt->dpBind(':id_cuadrilla', $this->getIdCuadrilla());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertCuadrilla(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_cuadrillas(id_contrato, default_id_vehiculo, default_id_area, nombre, actividad)
                values(:id_contrato, :default_id_vehiculo, :default_id_area, :nombre, :actividad)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':default_id_vehiculo', $this->getDefaultIdVehiculo());
        $stmt->dpBind(':default_id_area', $this->getDefaultIdArea());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':actividad', $this->getActividad());
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



    public static function uploadsUpload($directory, $name, $id_busqueda){
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



    public static function uploadsLoad($id_busqueda) {
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

    public static function uploadsDelete($name){
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