<?php
include_once("model/cuadrilla-empleadoModel.php");

class Cuadrilla
{
    private $id_cuadrilla;
    private $id_contrato;
    private $default_id_vehiculo;
    private $default_id_area;
    private $nombre;
    private $nombre_corto;
    private $actividad;

    private $conductores = array();
    private $acompanantes = array();

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

    function getNombreCorto()
    { return $this->nombre_corto;}

    function getActividad()
    { return $this->actividad;}

    function getConductores()
    {
        $rta = array();
        foreach ($this->conductores as $co){
            $rta[]= $co['id_empleado'];
        }
        return $rta;
    }

    function getAcompanantes()
    {
        $rta = array();
        foreach ($this->acompanantes as $ac){
            $rta[]= $ac['id_empleado'];
        }
        return $rta;
    }


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

    function setNombreCorto($val)
    { $this->nombre_corto=$val;}

    function setActividad($val)
    { $this->actividad=$val;}

    function setConductores($val)
    { $this->conductores=$val;}

    function setAcompanantes($val)
    { $this->acompanantes=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_cuadrilla, id_contrato, default_id_vehiculo,
                      default_id_area, nombre, nombre_corto, actividad
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
            $this->setNombreCorto($rows[0]['nombre_corto']);
            $this->setActividad($rows[0]['actividad']);

            $this->conductores = CuadrillaEmpleado::getCuadrillaEmpleado($this->getIdCuadrilla(), 1);
            $this->acompanantes = CuadrillaEmpleado::getCuadrillaEmpleado($this->getIdCuadrilla(), 0);
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
                  ve.nro_movil as vehiculo,
                  concat(ar.codigo, ' ', ar.nombre) as area
                  from nov_cuadrillas cu
                  join v_sec_contratos_control co on cu.id_contrato = co.id_contrato
                  left join vto_vehiculos ve on cu.default_id_vehiculo = ve.id_vehiculo
                  left join nov_areas ar on cu.default_id_area = ar.id_area
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
        $query = "select cu.id_cuadrilla, cu.id_contrato, cu.default_id_vehiculo, cu.default_id_area, cu.nombre, cu.actividad,
                  co.nombre as contrato,
                  concat(cast(ve.nro_movil as char), ' ', ve.modelo) as vehiculo,
                  concat(ar.codigo, ' ', ar.nombre) as area
                  from nov_cuadrillas cu
                  join contratos co on cu.id_contrato = co.id_contrato
                  left join vto_vehiculos ve on cu.default_id_vehiculo = ve.id_vehiculo
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
                'acompanantes'=>$unaCuadrilla->getAcompanantes(),
                'conductores'=>$unaCuadrilla->getConductores()
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
        $query="update nov_cuadrillas set
                      default_id_vehiculo = :default_id_vehiculo,
                      default_id_area = :default_id_area,
                      nombre = :nombre,
                      nombre_corto = :nombre_corto,
                      actividad = :actividad
                where id_cuadrilla =:id_cuadrilla";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':default_id_vehiculo', $this->getDefaultIdVehiculo());
        $stmt->dpBind(':default_id_area', $this->getDefaultIdArea());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':nombre_corto', $this->getNombreCorto());
        $stmt->dpBind(':actividad', $this->getActividad());
        $stmt->dpBind(':id_cuadrilla', $this->getIdCuadrilla());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertCuadrilla(){ //ok
        $stmt=new sQuery();
        $query="insert into nov_cuadrillas(id_contrato, default_id_vehiculo, default_id_area, nombre, nombre_corto, actividad)
                values(:id_contrato, :default_id_vehiculo, :default_id_area, :nombre, :nombre_corto, :actividad)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato', $this->getIdContrato());
        $stmt->dpBind(':default_id_vehiculo', $this->getDefaultIdVehiculo());
        $stmt->dpBind(':default_id_area', $this->getDefaultIdArea());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':nombre_corto', $this->getNombreCorto());
        $stmt->dpBind(':actividad', $this->getActividad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteCuadrilla(){ //ok
        $stmt=new sQuery();
        $query="delete from nov_cuadrillas where id_cuadrilla =:id_cuadrilla";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_cuadrilla', $this->getIdCuadrilla());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}




?>