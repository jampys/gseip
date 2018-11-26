<?php
include_once("empleadosModel.php");

class RenovacionPersonal
{
    private $id_renovacion;
    private $id_vencimiento;
    private $id_empleado;
    private $id_grupo;
    private $fecha_emision;
    private $fecha_vencimiento;
    private $fecha;
    private $id_rnv_renovacion; //id_renovacion que le sigue
    private $disabled;
    private $referencia;

    private $empleado;


    // GETTERS
    function getIdRenovacion()
    { return $this->id_renovacion;}

    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getIdGrupo()
    { return $this->id_grupo;}

    function getFechaEmision()
    { return $this->fecha_emision;}

    function getFechaVencimiento()
    { return $this->fecha_vencimiento;}

    function getFecha()
    { return $this->fecha;}

    function getEmpleado(){
        return ($this->empleado)? $this->empleado : new Empleado() ;
    }

    function getIdRnvRenovacion()
    { return $this->id_rnv_renovacion;}

    function getDisabled()
    { return $this->disabled;}

    function getReferencia()
    { return $this->referencia;}


    //SETTERS
    function setIdRenovacion($val)
    { $this->id_renovacion=$val;}

    function setIdVencimiento($val)
    {  $this->id_vencimiento=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}

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

    function setDisabled($val)
    { $this->disabled=$val;}

    function setReferencia($val)
    { $this->referencia=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_renovacion, id_vencimiento, id_empleado, id_grupo,
                    DATE_FORMAT(fecha_emision,  '%d/%m/%Y') as fecha_emision,
                    DATE_FORMAT(fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
                    DATE_FORMAT(fecha,  '%d/%m/%Y') as fecha, id_rnv_renovacion, disabled, referencia
                    from vto_renovacion_p
                    where id_renovacion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdRenovacion($rows[0]['id_renovacion']);
            $this->setIdVencimiento($rows[0]['id_vencimiento']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
            $this->setIdGrupo($rows[0]['id_grupo']);
            $this->setFechaEmision($rows[0]['fecha_emision']);
            $this->setFechaVencimiento($rows[0]['fecha_vencimiento']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdRnvRenovacion($rows[0]['id_rnv_renovacion']);
            $this->setDisabled($rows[0]['disabled']);
            $this->setReferencia($rows[0]['referencia']);

            $this->empleado = new Empleado($rows[0]['id_empleado']);
        }
    }


    public static function getRenovacionesPersonal($id_empleado, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado) { //ok
        $stmt=new sQuery();
        $query = "
        ( -- renovaciones por empleado
        select vrp.id_renovacion, vrp.id_vencimiento, vrp.id_empleado,
DATE_FORMAT(vrp.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(vrp.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(vrp.fecha,  '%d/%m/%Y') as fecha,
vvp.nombre as vencimiento,
vav.id_alerta, vav.days,
va.color, va.priority,
CONCAT(em.apellido, ' ', em.nombre) as empleado,
null  as grupo,
vrp.id_rnv_renovacion,
(select count(*) from uploads_vencimiento_p where id_renovacion = vrp.id_renovacion) as cant_uploads
from v_sec_vto_renovacion_p vrp, vto_vencimiento_p vvp, vto_alerta_vencimiento_p vav,
(
select emx.*, ecx.id_contrato
from empleados emx
left join empleado_contrato ecx on emx.id_empleado = ecx.id_empleado
left join subcontratista_empleado subemp on emx.id_empleado = subemp.id_empleado
where if(:id_subcontratista is not null, subemp.id_subcontratista = :id_subcontratista, (subemp.id_subcontratista = subemp.id_subcontratista or subemp.id_subcontratista is null))
and
(( -- filtro por contrato
  :id_contrato is not null
  and ecx.id_contrato = :id_contrato
  -- and datediff(ecx.fecha_hasta, date(sysdate())) >= 0
  and (ecx.fecha_hasta > date(sysdate()) or ecx.fecha_hasta is null)
 )
OR
( -- todos los contratos, o los sin contrato
 :id_contrato is null
 -- and ecx.id_contrato is null
 ))
 group by emx.id_empleado
 having emx.fecha_baja is null
) em,
vto_alerta va
where vrp.id_vencimiento = vvp.id_vencimiento
and vav.id_vencimiento = vrp.id_vencimiento
and vrp.id_empleado = em.id_empleado
and vav.id_alerta = va.id_alerta
and vav.id_alerta = func_alerta(vrp.id_renovacion)
and em.id_empleado =  ifnull(:id_empleado, em.id_empleado)
and vrp.id_vencimiento in ($id_vencimiento)
and ifnull(:renovado, vrp.id_rnv_renovacion is null)
and ifnull(:renovado, vrp.disabled is null)
and vrp.id_empleado is not null
and :id_grupo is null -- filtro empleados: no debe traer registros cuando se filtra por grupo
)
UNION
( -- renovaciones por grupo
select vrp.id_renovacion, vrp.id_vencimiento, vrp.id_empleado,
DATE_FORMAT(vrp.fecha_emision,  '%d/%m/%Y') as fecha_emision,
DATE_FORMAT(vrp.fecha_vencimiento,  '%d/%m/%Y') as fecha_vencimiento,
DATE_FORMAT(vrp.fecha,  '%d/%m/%Y') as fecha,
vvp.nombre as vencimiento,
vav.id_alerta, vav.days,
va.color, va.priority,
null as empleado,
CONCAT(vgp.nombre, ' ', ifnull(vgp.nro_referencia, '')) as grupo,
vrp.id_rnv_renovacion,
(select count(*) from uploads_vencimiento_p where id_renovacion = vrp.id_renovacion) as cant_uploads
from v_sec_vto_renovacion_p vrp, vto_vencimiento_p vvp, vto_alerta_vencimiento_p vav, vto_alerta va, vto_grupos_p vgp
where vrp.id_grupo = vgp.id_grupo
and vrp.id_vencimiento = vvp.id_vencimiento
and vav.id_vencimiento = vrp.id_vencimiento
and vav.id_alerta = va.id_alerta
and vav.id_alerta = func_alerta(vrp.id_renovacion)
and vrp.id_vencimiento in ($id_vencimiento) -- filtro por vencimiento
and vrp.id_grupo = ifnull(:id_grupo, vrp.id_grupo) -- filtro por grupo
and ifnull(:renovado, vrp.id_rnv_renovacion is null)
and ifnull(:renovado, vrp.disabled is null)
and vrp.id_empleado is null
and :id_empleado is null -- filtro empleados: no debe traer registros cuando se filtra por empleado
and :id_contrato is null -- filtro contratos: no debe traer registros cuando se filtra por contrato
and :id_subcontratista is null -- filtro subcontratistas: no debe traer registros cuando se filtra por subcontratista
)

order by priority, id_rnv_renovacion asc";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $id_empleado);
        $stmt->dpBind(':id_grupo', $id_grupo);
        //$stmt->dpBind(':id_vencimiento', $id_vencimiento);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_subcontratista', $id_subcontratista);
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
        $query="update vto_renovacion_p set id_vencimiento =:id_vencimiento,
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
                                        :referencia,
                                        @flag
                                    )';

        $stmt->dpPrepare($query);

        $stmt->dpBind(':id_vencimiento', $this->getIdVencimiento());
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_grupo', $this->getIdGrupo());
        $stmt->dpBind(':fecha_emision', $this->getFechaEmision());
        $stmt->dpBind(':fecha_vencimiento', $this->getFechaVencimiento());
        $stmt->dpBind(':referencia', $this->getReferencia());

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



    public static function uploadsUpload($directory, $name, $id_renovacion){ //ok
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



    public static function uploadsLoad($id_renovacion) { //ok
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

    public static function uploadsDelete($name){ //ok
        $stmt=new sQuery();
        $query="delete from uploads_vencimiento_p where name =:name";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':name', $name);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkFechaEmision($fecha_emision, $id_empleado, $id_grupo, $id_vencimiento, $id_renovacion) { //ok
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


    public function empleadosGrupos() { //ok
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
select null, id_grupo, concat(nombre, ' ', ifnull(nro_referencia, '')) as descripcion, id_vencimiento
from vto_grupos_p) eg
order by eg.descripcion";


        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }




}




?>