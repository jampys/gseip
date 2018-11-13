<?php

class Postulacion
{
    private $id_postulacion;
    private $id_busqueda;
    private $fecha;
    private $id_postulante;
    private $origen_cv;
    private $expectativas;
    private $propuesta_economica;

    // GETTERS
    function getIdPostulacion()
    { return $this->id_postulacion;}

    function getIdBusqueda()
    { return $this->id_busqueda;}

    function getIdPostulante()
    { return $this->id_postulante;}

    function getFecha()
    { return $this->fecha;}

    function getOrigenCv()
    { return $this->origen_cv;}

    function getExpectativas()
    { return $this->expectativas;}

    function getPropuestaEconomica()
    { return $this->propuesta_economica;}


    //SETTERS
    function setIdPostulacion($val)
    {  $this->id_postulacion=$val;}

    function setIdBusqueda($val)
    { $this->id_busqueda=$val;}

    function setIdPostulante($val)
    { $this->id_postulante=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setOrigenCv($val)
    { $this->origen_cv=$val;}

    function setExpectativas($val)
    { $this->expectativas=$val;}

    function setPropuestaEconomica($val)
    { $this->propuesta_economica=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_postulacion, id_busqueda, id_postulante,
                      origen_cv, expectativas, propuesta_economica
                      from sel_postulaciones
                      where id_postulacion = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdPostulacion($rows[0]['id_postulacion']);
            $this->setIdBusqueda($rows[0]['id_busqueda']);
            $this->setIdPostulante($rows[0]['id_postulante']);
            $this->setOrigenCv($rows[0]['origen_cv']);
            $this->setExpectativas($rows[0]['expectativas']);
            $this->setPropuestaEconomica($rows[0]['propuesta_economica']);
        }
    }


    public static function getPostulaciones($id_busqueda, $id_postulante, $todas) { //ok
        $stmt=new sQuery();
        $query = "select
                  (select !exists(select 1 from sel_etapas etx where  etx.id_postulacion = pos.id_postulacion and etx.aplica = '0')) as aplica,
                  pos.id_postulacion, pos.id_busqueda, pos.id_postulante,
                  DATE_FORMAT(pos.fecha,  '%d/%m/%Y') as fecha,
                  pos.origen_cv, pos.expectativas, pos.propuesta_economica,
                  CONCAT(po.apellido, ' ', po.nombre) as postulante,
                  bu.nombre as busqueda,
                  loc.ciudad
                  from sel_postulaciones pos
                  join sel_postulantes po on pos.id_postulante = po.id_postulante
                  join sel_busquedas bu on pos.id_busqueda = bu.id_busqueda
                  left join localidades loc on loc.id_localidad = bu.id_localidad
                  where pos.id_busqueda =  ifnull(:id_busqueda, pos.id_busqueda)
                  and pos.id_postulante =  ifnull(:id_postulante, pos.id_postulante)";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_busqueda', $id_busqueda);
        $stmt->dpBind(':id_postulante', $id_postulante);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_postulacion)
        {$rta = $this->updatePostulacion();}
        else
        {$rta =$this->insertPostulacion();}
        return $rta;
    }


    public function updatePostulacion(){ //ok
        $stmt=new sQuery();
        $query="update sel_postulaciones set id_busqueda =:id_busqueda,
                      id_postulante = :id_postulante,
                      origen_cv = :origen_cv,
                      expectativas = :expectativas,
                      propuesta_economica = :propuesta_economica
                where id_postulacion =:id_postulacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_busqueda', $this->getIdBusqueda());
        $stmt->dpBind(':id_postulante', $this->getIdPostulante());
        $stmt->dpBind(':origen_cv', $this->getOrigenCv());
        $stmt->dpBind(':expectativas', $this->getExpectativas());
        $stmt->dpBind(':propuesta_economica', $this->getPropuestaEconomica());
        $stmt->dpBind(':id_postulacion', $this->getIdPostulacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertPostulacion(){ //ok
        $stmt=new sQuery();
        $query="insert into sel_postulaciones(id_busqueda, id_postulante, fecha, origen_cv, expectativas, propuesta_economica)
                values(:id_busqueda, :id_postulante, sysdate(), :origen_cv, :expectativas, :propuesta_economica)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_busqueda', $this->getIdBusqueda());
        $stmt->dpBind(':id_postulante', $this->getIdPostulante());
        $stmt->dpBind(':origen_cv', $this->getOrigenCv());
        $stmt->dpBind(':expectativas', $this->getExpectativas());
        $stmt->dpBind(':propuesta_economica', $this->getPropuestaEconomica());
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