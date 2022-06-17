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
    private $id_rnv_renovacion; //id_renovacion que le sigue
    private $disabled;
    private $referencia;
    private $created_by;
    private $created_date;

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

    function getVehiculo(){
        return ($this->vehiculo)? $this->vehiculo : new Vehiculo() ;
    }

    function getIdRnvRenovacion()
    { return $this->id_rnv_renovacion;}

    function getDisabled()
    { return $this->disabled;}

    function getReferencia()
    { return $this->referencia;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}


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

    function setIdRnvRenovacion($val)
    { $this->id_rnv_renovacion=$val;}

    function setDisabled($val)
    { $this->disabled=$val;}

    function setReferencia($val)
    { $this->referencia=$val;}

    function setCreatedBy($val)
    { $this->created_by=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_renovacion, id_vencimiento, id_vehiculo, id_grupo,
                    DATE_FORMAT(fecha_emision,  '%d/%m/%Y') as fecha_emision,
                    DATE_FORMAT(fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
                    DATE_FORMAT(created_date,  '%d/%m/%Y') as created_date,
                    id_rnv_renovacion, disabled, referencia
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
            $this->setCreatedDate($rows[0]['created_date']);
            $this->setIdRnvRenovacion($rows[0]['id_rnv_renovacion']);
            $this->setDisabled($rows[0]['disabled']);
            $this->setReferencia($rows[0]['referencia']);

            $this->vehiculo = new Vehiculo($rows[0]['id_vehiculo']);
        }
    }


    public static function getRenovacionesVehiculos($id_vehiculo, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado) { //ok
        $stmt=new sQuery();
        $query = "
        ( -- renovaciones por vehiculo
        select vrv.id_renovacion, vrv.id_vencimiento, vrv.id_vehiculo,
DATE_FORMAT(vrv.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(vrv.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(vrv.created_date,  '%d/%m/%Y') as created_date,
vvv.nombre as vencimiento,
vav.id_alerta, vav.days,
va.color, va.priority,
concat(ifnull(matricula, ''), ' ', ifnull(nro_movil, ''), ' ', ifnull(modelo, '')) as vehiculo,
null  as grupo,
vrv.id_rnv_renovacion,
(select count(*) from uploads_vencimiento_v where id_renovacion = vrv.id_renovacion) as cant_uploads,
null as certificado, us.user
from v_sec_vto_renovacion_v vrv, vto_vencimiento_v vvv, vto_alerta_vencimiento_v vav,
(
select vex.*, vvcx.id_contrato
from vto_vehiculos vex
left join vto_vehiculo_contrato vvcx on vex.id_vehiculo = vvcx.id_vehiculo
left join subcontratista_vehiculo subveh on vex.id_vehiculo = subveh.id_vehiculo
where if(:id_subcontratista is not null, subveh.id_subcontratista = :id_subcontratista, (subveh.id_subcontratista = subveh.id_subcontratista or subveh.id_subcontratista is null))
and
(( -- filtro por contrato
  :id_contrato is not null
  and vvcx.id_contrato = :id_contrato
  and (vvcx.fecha_hasta > date(sysdate()) or vvcx.fecha_hasta is null)
 )
OR
( -- todos los contratos, o los sin contrato
 :id_contrato is null
 -- and vvcx.id_contrato is null
 ))
 group by vex.id_vehiculo
 having vex.fecha_baja is null
) ve,
vto_alerta va, sec_users us
where vrv.id_vencimiento = vvv.id_vencimiento
and vav.id_vencimiento = vrv.id_vencimiento
and vrv.id_vehiculo = ve.id_vehiculo
and vrv.created_by = us.id_user
and vav.id_alerta = va.id_alerta
and vav.id_alerta = func_alerta_vehicular(vrv.id_renovacion)
and ve.id_vehiculo =  ifnull(:id_vehiculo, ve.id_vehiculo)
and vrv.id_vencimiento in ($id_vencimiento)
and ifnull(:renovado, vrv.id_rnv_renovacion is null)
and ifnull(:renovado, vrv.disabled is null)
and vrv.id_vehiculo is not null
and :id_grupo is null -- filtro vehiculos: no debe traer registros cuando se filtra por grupo
)
UNION
( -- renovaciones por grupo
select vrv.id_renovacion, vrv.id_vencimiento, vrv.id_vehiculo,
DATE_FORMAT(vrv.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(vrv.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(vrv.created_date,  '%d/%m/%Y') as created_date,
vvv.nombre as vencimiento,
vav.id_alerta, vav.days,
va.color, va.priority,
null as vehiculo,
CONCAT(vgv.nombre, ' ', ifnull(vgv.nro_referencia, '')) as grupo,
vrv.id_rnv_renovacion,
(select count(*) from uploads_vencimiento_v where id_renovacion = vrv.id_renovacion) as cant_uploads,
(select vgvx.certificado from vto_grupo_vehiculo vgvx where vgvx.id_grupo = vgv.id_grupo and vgvx.id_vehiculo = :id_vehiculo and (vgvx.fecha_hasta is null or vgvx.fecha_hasta >= sysdate())) as certificado,
us.user
from v_sec_vto_renovacion_v vrv, vto_vencimiento_v vvv, vto_alerta_vencimiento_v vav, vto_alerta va, vto_grupos_v vgv, sec_users us
where vrv.id_grupo = vgv.id_grupo
and vrv.id_vencimiento = vvv.id_vencimiento
and vav.id_vencimiento = vrv.id_vencimiento
and vrv.created_by = us.id_user
and vav.id_alerta = va.id_alerta
and vav.id_alerta = func_alerta_vehicular(vrv.id_renovacion)
and vrv.id_vencimiento in ($id_vencimiento) -- filtro por vencimiento
and vrv.id_grupo = ifnull(:id_grupo, vrv.id_grupo) -- filtro por grupo
and ifnull(:renovado, vrv.id_rnv_renovacion is null)
and ifnull(:renovado, vrv.disabled is null)
and vrv.id_vehiculo is null
and :id_contrato is null -- filtro contratos: no debe traer registros cuando se filtra por contrato
and :id_subcontratista is null -- filtro subcontratistas: no debe traer registros cuando se filtra por subcontratista
and vgv.id_grupo in (
                    select vgx.id_grupo
                    from vto_grupo_vehiculo vgvx
                    join vto_grupos_v vgx on vgx.id_grupo = vgvx.id_grupo
                    and vgvx.id_vehiculo = ifnull(:id_vehiculo, vgvx.id_vehiculo)
                    and (vgvx.fecha_hasta is null or vgvx.fecha_hasta >= sysdate())
                    group by vgx.id_grupo
                    )
)

order by priority, id_rnv_renovacion asc";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpBind(':id_grupo', $id_grupo);
        //$stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_subcontratista', $id_subcontratista);
        $stmt->dpBind(':renovado', $renovado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }




    public static function getRenovacionesVehiculosAuditoria($id_vehiculo, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado) { //ok
        $stmt=new sQuery();
        $query = "select v.id_vencimiento, v.nombre as vencimiento,
ve.id_vehiculo, ve.nro_movil, ve.matricula, ve.modelo,
vv.id_vehiculo_vencimiento,
vrv.id_renovacion, vrv.fecha_emision,
DATE_FORMAT(vrv.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
vrv.id_rnv_renovacion, vrv.referencia, vrv.comentarios,
DATE_FORMAT(vrv.disabled,  '%d/%m/%Y') as disabled,
datediff(vrv.fecha_vencimiento, sysdate()) as isVencida
from vto_vencimiento_v v
join vto_vehiculos ve
left join vto_vehiculo_vencimiento vv on vv.id_vencimiento = v.id_vencimiento and vv.id_vehiculo = ve.id_vehiculo
left join vto_renovacion_v vrv on vrv.id_vencimiento = v.id_vencimiento and vrv.id_vehiculo = ve.id_vehiculo and vrv.id_rnv_renovacion is null
left join vto_vehiculo_contrato vc on vc.id_vehiculo = ve.id_vehiculo
where ve.id_vehiculo = ifnull(:id_vehiculo, ve.id_vehiculo)
and if(:id_contrato is not null, vc.id_contrato = :id_contrato, 1)
and v.id_vencimiento in ($id_vencimiento)
and ve.fecha_baja is null
group by v.id_vencimiento, ve.id_vehiculo";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        //$stmt->dpBind(':id_grupo', $id_grupo);
        //$stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':id_subcontratista', $id_subcontratista);
        //$stmt->dpBind(':renovado', $renovado);
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
                      fecha_vencimiento = STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y'),
                      disabled = STR_TO_DATE(:disabled, '%d/%m/%Y'),
                      referencia = :referencia
                where id_renovacion =:id_renovacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpBind(':disabled', $this->getDisabled());
        $stmt->dpBind(':referencia', $this->getReferencia());
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
                                        :referencia,
                                        :created_by,
                                        @flag
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':id_vehiculo', $this->getIdVehiculo());
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpBind(':referencia', $this->getReferencia());
        $stmt->dpBind(':created_by', $this->getCreatedBy());

        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : -1;
    }


    function deleteRenovacion(){ //ok
        $stmt=new sQuery();
        $query="delete from vto_renovacion_v
                where id_renovacion = :id_renovacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_renovacion', $this->getIdRenovacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsUpload($directory, $name, $id_renovacion){ //ok
        $stmt=new sQuery();
        $query="insert into uploads_vencimiento_v(directory, name, fecha, id_renovacion)
                values(:directory, :name, date(sysdate()), :id_renovacion)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':name', $name);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsLoad($id_renovacion) { //ok
        $stmt=new sQuery();
        $query = "select id_upload, directory, name, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, id_renovacion
                from uploads_vencimiento_v
                where id_renovacion = :id_renovacion
                order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_renovacion', $id_renovacion);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function uploadsDelete($name){ //ok
        $stmt=new sQuery();
        $query="delete from uploads_vencimiento_v where name =:name";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':name', $name);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkRangoFechas($fecha_emision, $fecha_vencimiento, $id_vehiculo, $id_grupo, $id_vencimiento, $id_renovacion) { //ok
        $stmt=new sQuery();
        $query = "select *
                  from vto_renovacion_v
                  where
                  ( -- renovar: busca renovacion vigente y se asegura que la fecha_vencimiento ingresada sea mayor que la de ésta
                  :id_renovacion is null
                  and (id_vehiculo = :id_vehiculo or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and (fecha_emision >= STR_TO_DATE(:fecha_emision, '%d/%m/%Y')
                      or fecha_vencimiento >= STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y'))
                  )
                  OR
                  ( -- editar: busca renovacion anterior y ....
                  :id_renovacion is not null
                  and (id_vehiculo = :id_vehiculo or id_grupo = :id_grupo)
				  and id_vencimiento = :id_vencimiento
                  and (fecha_emision >= STR_TO_DATE(:fecha_emision, '%d/%m/%Y')
                      or fecha_vencimiento >= STR_TO_DATE(:fecha_vencimiento, '%d/%m/%Y'))
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
select null, id_grupo, concat(nombre, ' ', ifnull(nro_referencia, '')) as descripcion, id_vencimiento
from vto_grupos_v) eg
order by eg.descripcion";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }




}




?>