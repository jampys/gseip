<?php

class GrupoVehiculo
{
    private $id_etapa;
    private $id_postulacion;
    private $fecha; //fecha de registro en el sistema
    private $fecha_etapa; //fecha en que se realizó la etapa. Puede no coincidir con la fecha de carga.
    private $etapa; //nombre de la etapa
    private $aplica;
    private $motivo;
    private $modo_contacto;
    private $comentarios;
    private $id_user;

    // GETTERS
    function getIdEtapa()
    { return $this->id_etapa;}

    function getIdPostulacion()
    { return $this->id_postulacion;}

    function getFecha()
    { return $this->fecha;}

    function getFechaEtapa()
    { return $this->fecha_etapa;}

    function getEtapa()
    { return $this->etapa;}

    function getAplica()
    { return $this->aplica;}

    function getMotivo()
    { return $this->motivo;}

    function getModoContacto()
    { return $this->modo_contacto;}

    function getComentarios()
    { return $this->comentarios;}

    function getIdUser()
    { return $this->id_user;}


    //SETTERS
    function setIdEtapa($val)
    { $this->id_etapa=$val;}

    function setIdPostulacion($val)
    {  $this->id_postulacion=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setFechaEtapa($val)
    { $this->fecha_etapa=$val;}

    function setEtapa($val)
    { $this->etapa=$val;}

    function setAplica($val)
    { $this->aplica=$val;}

    function setMotivo($val)
    { $this->motivo=$val;}

    function setModoContacto($val)
    { $this->modo_contacto=$val;}

    function setComentarios($val)
    { $this->comentarios=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}


    function __construct($nro=0){ //constructor

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


    public static function getVehiculos($id_grupo) {  //ok
        $stmt=new sQuery();
        $query = "select gv.id_grupo_vehiculo, gv.id_vehiculo, gv.id_grupo,
                  DATE_FORMAT(gv.fecha_desde, '%d/%m/%Y') as fecha_desde,
                  DATE_FORMAT(gv.fecha_hasta, '%d/%m/%y') as fecha_hasta,
                  ve.matricula, ve.nro_movil
                  from vto_grupo_vehiculo gv
                  join vto_vehiculos ve on ve.id_vehiculo = gv.id_vehiculo
                  where gv.id_grupo = :id_grupo
                  order by gv.fecha_desde asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_grupo', $id_grupo);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){
        if($this->id_etapa)
        {$rta = $this->updateEtapa();}
        else
        {$rta =$this->insertEtapa();}
        return $rta;
    }


    public function updateEtapa(){
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

    private function insertEtapa(){
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

    function deleteEtapa(){
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