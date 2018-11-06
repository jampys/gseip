<?php

class GrupoVehiculo
{
    private $id_grupo;
    private $nombre;
    private $id_vencimiento;
    private $nro_referencia;
    private $fecha_baja;

    // GETTERS
    function getIdGrupo()
    { return $this->id_grupo;}

    function getNombre()
    { return $this->nombre;}

    function getIdVencimiento()
    { return $this->id_vencimiento;}

    function getNroReferencia()
    { return $this->nro_referencia;}

    function getFechaBaja()
    { return $this->fecha_baja;}


    //SETTERS
    function setIdGrupo($val)
    {  $this->id_grupo=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setIdVencimiento($val)
    { $this->id_vencimiento=$val;}

    function setNroReferencia($val)
    { $this->nro_referencia=$val;}

    function setFechaBaja($val)
    { $this->fecha_baja=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_grupo, nombre, id_vencimiento,
                      nro_referencia,
                      DATE_FORMAT(fecha_baja,  '%d/%m/%Y') as fecha_baja
                      from vto_grupos_v
                      where id_grupo = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdGrupo($rows[0]['id_grupo']);
            $this->setNombre($rows[0]['nombre']);
            $this->setIdVencimiento($rows[0]['id_vencimiento']);
            $this->setNroReferencia($rows[0]['nro_referencia']);
            $this->setFechaBaja($rows[0]['fecha_baja']);
        }
    }


    public static function getGrupos() { //ok
        $stmt=new sQuery();
        $query="select gru.id_grupo, gru.nombre, gru.id_vencimiento, gru.nro_referencia,
                DATE_FORMAT(gru.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                vto.nombre as vencimiento
                from vto_grupos_v gru
                join vto_vencimiento_v vto on gru.id_vencimiento = vto.id_vencimiento
                order by nombre asc";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){
        if($this->id_postulacion)
        {$rta = $this->updatePostulacion();}
        else
        {$rta =$this->insertPostulacion();}
        return $rta;
    }


    public function updatePostulacion(){
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

    private function insertPostulacion(){
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