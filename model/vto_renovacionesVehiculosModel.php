<?php
include_once("vehiculosModel.php");

class RenovacionVehicular
{
    private $id_renovacion;
    private $id_vencimiento;
    private $id_vehiculo;
    private $id_grupo;
    private $fecha_emision;
    private $fecha_vencimiento;
    private $fecha;
    private $id_rnv_renovacion; //id_renovacion que le sigue

    private $vehiculo;


    // GETTERS
    function getIdRenovacion()
    { return $this->id_renovacion;}

    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getIdVehiculo()
    { return $this->id_vehiculo;}

    function getIdGrupo()
    { return $this->id_grupo;}

    function getFechaEmision()
    { return $this->fecha_emision;}

    function getFechaVencimiento()
    { return $this->fecha_vencimiento;}

    function getFecha()
    { return $this->fecha;}

    function getVehiculo(){
        return ($this->vehiculo)? $this->vehiculo : new Vehiculo() ;
    }

    function getIdRnvRenovacion()
    { return $this->id_rnv_renovacion;}


    //SETTERS
    function setIdRenovacion($val)
    { $this->id_renovacion=$val;}

    function setIdVencimiento($val)
    {  $this->id_vencimiento=$val;}

    function setIdVehiculo($val)
    { $this->id_vehiculo=$val;}

    function setIdGrupo($val)
    { $this->id_grupo=$val;}

    function setFechaEmision($val)
    { $this->fecha_emision=$val;}

    function setFechaVencimiento($val)
    { $this->fecha_vencimiento=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIdRnvRenovacion($val)
    { $this->id_rnv_renovacion=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_renovacion, id_vencimiento, id_vehiculo, id_grupo,
                    DATE_FORMAT(fecha_emision,  '%d/%m/%Y') as fecha_emision,
                    DATE_FORMAT(fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
                    DATE_FORMAT(fecha,  '%d/%m/%Y') as fecha, id_rnv_renovacion
                    from vto_renovacion_v
                    where id_renovacion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdRenovacion($rows[0]['id_renovacion']);
            $this->setIdVencimiento($rows[0]['id_vencimiento']);
            $this->setIdVehiculo($rows[0]['id_vehiculo']);
            $this->setIdGrupo($rows[0]['id_grupo']);
            $this->setFechaEmision($rows[0]['fecha_emision']);
            $this->setFechaVencimiento($rows[0]['fecha_vencimiento']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdRnvRenovacion($rows[0]['id_rnv_renovacion']);

            $this->vehiculo = new Vehiculo($rows[0]['id_vehiculo']);
        }
    }


    public static function getRenovacionesVehiculos($id_vehiculo, $id_grupo, $id_vencimiento, $id_contrato, $renovado) { //ok
        $stmt=new sQuery();
        $query = "
        ( -- renovaciones por vehiculo
        select vrv.id_renovacion, vrv.id_vencimiento, vrv.id_vehiculo,
DATE_FORMAT(vrv.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(vrv.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(vrv.fecha,  '%d/%m/%Y') as fecha,
vvv.nombre as vencimiento,
vav.id_alerta, vav.days,
va.color, va.priority,
concat(ifnull(matricula, ''), ' ', ifnull(nro_movil, ''), ' ', ifnull(modelo, '')) as vehiculo,
null  as grupo,
vrv.id_rnv_renovacion,
(select count(*) from uploads_vencimiento_v where id_renovacion = vrv.id_renovacion) as cant_uploads
from v_sec_vto_renovacion_v vrv, vto_vencimiento_v vvv, vto_alerta_vencimiento_p vav,
(
select vex.*, vvcx.id_contrato
from vto_vehiculos vex
left join vto_vehiculo_contrato vvcx on vex.id_vehiculo = vvcx.id_vehiculo
where
( -- filtro por contrato
  :id_contrato is not null
  and vvcx.id_contrato = :id_contrato
  and (vvcx.fecha_hasta > date(sysdate()) or vvcx.fecha_hasta is null)
 )
OR
( -- todos los contratos, o los sin contrato
 :id_contrato is null
 -- and vvcx.id_contrato is null
 )
 group by vex.id_vehiculo
 having vex.fecha_baja is null
) ve,
vto_alerta va
where vrv.id_vencimiento = vvv.id_vencimiento
and vav.id_vencimiento = vrv.id_vencimiento
and vrv.id_vehiculo = ve.id_vehiculo
and vav.id_alerta = va.id_alerta
and vav.id_alerta = func_alerta_vehicular(vrv.id_renovacion)
and ve.id_vehiculo =  ifnull(:id_vehiculo, ve.id_vehiculo)
and vrv.id_vencimiento = ifnull(:id_vencimiento, vrv.id_vencimiento)
and ifnull(:renovado, vrv.id_rnv_renovacion is null)
and vrv.id_vehiculo is not null
and :id_grupo is null -- filtro vehiculos: no debe traer registros cuando se filtra por grupo
)
UNION
( -- renovaciones por grupo
select vrv.id_renovacion, vrv.id_vencimiento, vrv.id_vehiculo,
DATE_FORMAT(vrv.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(vrv.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(vrv.fecha,  '%d/%m/%Y') as fecha,
vvv.nombre as vencimiento,
vav.id_alerta, vav.days,
va.color, va.priority,
null as vehiculo,
CONCAT(vgv.nombre, ' ', vgv.numero) as grupo,
vrv.id_rnv_renovacion,
(select count(*) from uploads_vencimiento_v where id_renovacion = vrv.id_renovacion) as cant_uploads
from v_sec_vto_renovacion_v vrv, vto_vencimiento_v vvv, vto_alerta_vencimiento_v vav, vto_alerta va, vto_grupos_v vgv
where vrv.id_grupo = vgv.id_grupo
and vrv.id_vencimiento = vvv.id_vencimiento
and vav.id_vencimiento = vrv.id_vencimiento
and vav.id_alerta = va.id_alerta
and vav.id_alerta = func_alerta_vehicular(vrv.id_renovacion)
and vrv.id_vencimiento = ifnull(:id_vencimiento, vrv.id_vencimiento) -- filtro por vencimiento
and vrv.id_grupo = ifnull(:id_grupo, vrv.id_grupo) -- filtro por grupo
and ifnull(:renovado, vrv.id_rnv_renovacion is null)
and vrv.id_vehiculo is null
and :id_vehiculo is null -- filtro vehiculos: no debe traer registros cuando se filtra por vehiculo
and :id_contrato is null -- filtro contratos: no debe traer registros cuando se filtra por contrato
)

order by priority, id_rnv_renovacion asc";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpBind(':id_grupo', $id_grupo);
        $stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':renovado', $renovado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_renovacion)
        {$rta = $this->updateRenovacion();}
        else
        {$rta =$this->insertRenovacion();}
        return $rta;
    }


    public function updateRenovacion(){ //ok
        $stmt=new sQuery();
        $query="update vto_renovacion_v set id_vencimiento =:id_vencimiento,
                      fecha_emision = STR_TO_DATE(:fecha_emision, '%d/%m/%Y'),
                      fecha_vencimiento = STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y')
                where id_renovacion =:id_renovacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpBind(':id_renovacion', $this->getIdRenovacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertRenovacion(){ //ok

        $stmt=new sQuery();
        $query = 'CALL sp_insertRenovacionVehicular(:id_vencimiento,
                                        :id_vehiculo,
                                        :id_grupo,
                                        :fecha_emision,
                                        :fecha_vencimiento,
                                        @flag
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
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
        $query = "select *
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


    public function checkFechaEmision($fecha_emision, $id_vehiculo, $id_grupo, $id_vencimiento, $id_renovacion) { //ok
        /*Busca la renovacion vigente para el id_empleado y id_vencimiento y se asegura que la proxima fecha_emision
        sea mayor. */
        $stmt=new sQuery();
        $query = "select *
                  from vto_renovacion_v
                  where
                  ( -- renovar: busca renovacion vigente y se asegura que la fecha_emision ingresada sea mayor que la de ésta
                  :id_renovacion is null
                  and (id_vehiculo = :id_vehiculo or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and fecha_emision >= STR_TO_DATE(:fecha_emision, '%d/%m/%Y')
                  )
                  OR
                  ( -- editar: busca renovacion anterior y ....
                  :id_renovacion is not null
                  and (id_vehiculo = :id_vehiculo or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and fecha_emision >= STR_TO_DATE(:fecha_emision, '%d/%m/%Y')
                  and id_renovacion <> :id_renovacion
                  )
                  order by fecha_emision asc
                  limit 1";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpBind(':fecha_emision', $fecha_emision);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpBind(':id_grupo', $id_grupo);
        $stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }

    public function checkFechaVencimiento($fecha_emision, $fecha_vencimiento, $id_vehiculo, $id_grupo, $id_vencimiento, $id_renovacion) { //ok
        $stmt=new sQuery();
        $query = "select *
                  from vto_renovacion_v
                  where
                  ( -- renovar: busca renovacion vigente y se asegura que la fecha_vencimiento ingresada sea mayor que la de ésta
                  :id_renovacion is null
                  and (id_vehiculo = :id_vehiculo or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and fecha_vencimiento >= STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y')
                  )
                  OR
                  ( -- editar: busca renovacion anterior y ....
                  :id_renovacion is not null
                  and (id_vehiculo = :id_vehiculo or id_grupo = :id_grupo)
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
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpBind(':id_grupo', $id_grupo);
        $stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }


    public function vehiculosGrupos() { //ok
        $stmt=new sQuery();
        $query = "select * FROM
(select id_vehiculo, null as id_grupo, concat(ifnull(matricula, ''), ' ', ifnull(nro_movil, ''), ' ', ifnull(modelo, '')) as descripcion, null as id_vencimiento
from vto_vehiculos
where fecha_baja is null
UNION
select null, id_grupo, concat(nombre, ' ', ifnull(numero, '')) as descripcion, id_vencimiento
from vto_grupos_v) eg
order by eg.descripcion";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }




}




?>