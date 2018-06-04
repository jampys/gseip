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
                  left join contratos co on bu.id_contrato = co.id_contrato";

        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_empleado', $id_empleado);
        //$stmt->dpBind(':id_grupo', $id_grupo);
        //$stmt->dpBind(':id_vencimiento', $id_vencimiento);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':renovado', $renovado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){
        if($this->id_renovacion)
        {$rta = $this->updateRenovacion();}
        else
        {$rta =$this->insertRenovacion();}
        return $rta;
    }


    public function updateRenovacion(){
        $stmt=new sQuery();
        $query="update vto_renovacion_p set id_vencimiento =:id_vencimiento,
                      fecha_emision = STR_TO_DATE(:fecha_emision, '%d/%m/%Y'),
                      fecha_vencimiento = STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y'),
                      disabled = STR_TO_DATE(:disabled, '%d/%m/%Y')
                where id_renovacion =:id_renovacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpBind(':disabled', $this->getDisabled());
        $stmt->dpBind(':id_renovacion', $this->getIdRenovacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertRenovacion(){
        /*$stmt=new sQuery();
        $query="insert into vto_renovacion_p(id_vencimiento, id_empleado, id_grupo, fecha_emision, fecha_vencimiento, fecha)
                values(:id_vencimiento, :id_empleado, :id_grupo, STR_TO_DATE(:fecha_emision, '%d/%m/%Y'), STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y'), date(sysdate()))";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpExecute();
        return $stmt->dpGetAffect(); */

        $stmt=new sQuery();
        $query = 'CALL sp_insertRenovacionPersonal(:id_vencimiento,
                                        :id_empleado,
                                        :id_grupo,
                                        :fecha_emision,
                                        :fecha_vencimiento,
                                        @flag
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : -1;
    }

    function deleteHabilidad(){
        $stmt=new sQuery();
        $query="delete from habilidades where id_habilidad =:id_habilidad";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_habilidad', $this->getIdHabilidad());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsUpload($directory, $name, $id_renovacion){
        $stmt=new sQuery();
        $query="insert into uploads_vencimiento_p(directory, name, fecha, id_renovacion)
                values(:directory, :name, date(sysdate()), :id_renovacion)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':name', $name);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsLoad($id_renovacion) {
        $stmt=new sQuery();
        $query = "select id_upload, directory, name, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, id_renovacion
                from uploads_vencimiento_p
                where id_renovacion = :id_renovacion
                order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function uploadsDelete($name){
        $stmt=new sQuery();
        $query="delete from uploads_vencimiento_p where name =:name";
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


    public function empleadosGrupos() {
        $stmt=new sQuery();
        /*$query = "select id_empleado, null as id_grupo, concat(apellido, ' ', nombre) as descripcion, null as id_vencimiento
from empleados
where fecha_baja is null
UNION
select null, id_grupo, concat(nombre, ' ', ifnull(numero, '')) as descripcion, id_vencimiento
from vto_grupos_p";*/
        $query = "select * FROM
(select id_empleado, null as id_grupo, concat(apellido, ' ', nombre) as descripcion, null as id_vencimiento
from empleados
where fecha_baja is null
UNION
select null, id_grupo, concat(nombre, ' ', ifnull(numero, '')) as descripcion, id_vencimiento
from vto_grupos_p) eg
order by eg.descripcion";


        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }




}




?>