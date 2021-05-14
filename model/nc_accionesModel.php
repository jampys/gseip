<?php

class Accion
{
    private $id_accion;
    private $id_no_conformidad;
    private $accion; //descripcion de la accion
    private $id_responsable_ejecucion;
    private $fecha_implementacion;
    private $id_user;
    private $created_date;

    // GETTERS
    function getIdAccion()
    { return $this->id_accion;}

    function getIdNoConformidad()
    { return $this->id_no_conformidad;}

    function getAccion()
    { return $this->accion;}

    function getIdResponsableEjecucion()
    { return $this->id_responsable_ejecucion;}

    function getFechaImplementacion()
    { return $this->fecha_implementacion;}

    function getIdUser()
    { return $this->id_user;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdAccion($val)
    { $this->id_accion=$val;}

    function setIdNoConformidad($val)
    {  $this->id_no_conformidad=$val;}

    function setIdResponsableEjecucion($val)
    { $this->id_responsable_ejecucion=$val;}

    function setFechaImplementacion($val)
    { $this->fecha_implementacion=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setCreatedDate($val)
    { $this->created_date=$val;}
    

    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_etapa, id_postulacion,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      DATE_FORMAT(fecha_etapa, '%d/%m/%Y') as fecha_etapa,
                      etapa, aplica, motivo, modo_contacto, comentarios, id_user
                      from sel_etapas
                      where id_etapa = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEtapa($rows[0]['id_etapa']);
            $this->setIdPostulacion($rows[0]['id_postulacion']);
            $this->setFecha($rows[0]['fecha']);
            $this->setFechaEtapa($rows[0]['fecha_etapa']);
            $this->setEtapa($rows[0]['etapa']);
            $this->setAplica($rows[0]['aplica']);
            $this->setMotivo($rows[0]['motivo']);
            $this->setModoContacto($rows[0]['modo_contacto']);
            $this->setComentarios($rows[0]['comentarios']);
            $this->setIdUser($rows[0]['id_user']);
        }
    }


    public static function getEtapas($id_postulacion) { //ok
        $stmt=new sQuery();
        $query = "select et.id_etapa, et.id_postulacion,
                  DATE_FORMAT(et.fecha, '%d/%m/%Y') as fecha,
                  DATE_FORMAT(et.fecha_etapa, '%d/%m/%y') as fecha_etapa,
                  et.etapa, et.aplica, et.motivo, et.modo_contacto, et.comentarios, et.id_user,
                  us.user
                  from sel_etapas et
                  join sec_users us on et.id_user = us.id_user
                  where et.id_postulacion = :id_postulacion
                  order by et.fecha_etapa asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulacion', $id_postulacion);
        //$stmt->dpBind(':id_grupo', $id_grupo);
        //$stmt->dpBind(':id_vencimiento', $id_vencimiento);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':renovado', $renovado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_etapa)
        {$rta = $this->updateEtapa();}
        else
        {$rta =$this->insertEtapa();}
        return $rta;
    }


    public function updateEtapa(){ //ok
        $stmt=new sQuery();
        $query="update sel_etapas set fecha_etapa = STR_TO_DATE(:fecha_etapa, '%d/%m/%Y'),
                etapa = :etapa,
                aplica = :aplica,
                motivo = :motivo,
                modo_contacto = :modo_contacto,
                comentarios = :comentarios
                where id_etapa = :id_etapa";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':fecha_etapa', $this->getFechaEtapa());
        $stmt->dpBind(':etapa', $this->getEtapa());
        $stmt->dpBind(':aplica', $this->getAplica());
        $stmt->dpBind(':motivo', $this->getMotivo());
        $stmt->dpBind(':modo_contacto', $this->getModoContacto());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_etapa', $this->getIdEtapa());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertEtapa(){ //ok
        $stmt=new sQuery();
        $query="insert into sel_etapas(id_postulacion, fecha, fecha_etapa, etapa, aplica, motivo , modo_contacto, comentarios, id_user)
                values(:id_postulacion, sysdate(), STR_TO_DATE(:fecha_etapa, '%d/%m/%Y'), :etapa, :aplica, :motivo, :modo_contacto, :comentarios, :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulacion', $this->getIdPostulacion());
        $stmt->dpBind(':fecha_etapa', $this->getFechaEtapa());
        $stmt->dpBind(':etapa', $this->getEtapa());
        $stmt->dpBind(':aplica', $this->getAplica());
        $stmt->dpBind(':motivo', $this->getMotivo());
        $stmt->dpBind(':modo_contacto', $this->getModoContacto());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deleteEtapa(){ //ok
        $stmt=new sQuery();
        $query="delete from sel_etapas where id_etapa = :id_etapa";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_etapa', $this->getIdEtapa());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsUpload($directory, $name, $id_postulante){
        $stmt=new sQuery();
        $query="insert into uploads_postulante(directory, name, fecha, id_postulante)
                values(:directory, :name, date(sysdate()), :id_postulante)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':name', $name);
        $stmt->dpBind(':id_postulante', $id_postulante);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    public static function uploadsLoad($id_postulante) {
        $stmt=new sQuery();
        $query = "select id_upload, directory, name, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, id_postulante
                from uploads_postulante
                where id_postulante = :id_postulante
                order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulante', $id_postulante);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function uploadsDelete($name){
        $stmt=new sQuery();
        $query="delete from uploads_postulante where name =:name";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':name', $name);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkDni($dni, $id_postulante) {
        $stmt=new sQuery();
        $query = "select *
                  from sel_postulantes
                  where dni = :dni
                  and id_postulante <> :id_postulante";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':dni', $dni);
        $stmt->dpBind(':id_postulante', $id_postulante);
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