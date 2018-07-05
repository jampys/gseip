<?php

class CuadrillaEmpleado
{
    private $id_cuadrilla_empleado;
    private $fecha; //fecha de registro en el sistema
    private $id_cuadrilla;
    private $id_empleado;

    // GETTERS
    function getIdCuadrillaEmpleado()
    { return $this->id_cuadrilla_empleado;}

    function getFecha()
    { return $this->fecha;}

    function getIdCuadrilla()
    { return $this->id_cuadrilla;}

    function getIdEmpleado()
    { return $this->id_empleado;}


    //SETTERS
    function setIdCuadrillaEmpleado($val)
    { $this->id_cuadrilla_empleado=$val;}

    function setFecha($val)
    { $this->fecha=$val;}

    function setIdCuadrilla($val)
    {  $this->id_cuadrilla=$val;}

    function setIdEmpleado($val)
    { $this->id_empleado=$val;}



    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_cuadrilla_empleado,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      id_cuadrilla, id_empleado
                      from nov_cuadrilla_empleado
                      where id_cuadrilla_empleado = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdCuadrillaEmpleado($rows[0]['id_cuadrilla_empleado']);
            $this->setFecha($rows[0]['fecha']);
            $this->setIdCuadrilla($rows[0]['id_cuadrilla']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
        }
    }


    public static function getCuadrillaEmpleado($id_cuadrilla) { //ok
        $stmt=new sQuery();
        $query = "select nce.id_cuadrilla_empleado,
                  DATE_FORMAT(nce.fecha, '%d/%m/%Y') as fecha,
                  nce.id_cuadrilla, nce.id_empleado,
                  em.apellido, em.nombre
                  from nov_cuadrilla_empleado nce
                  join empleados em on nce.id_empleado = em.id_empleado
                  where nce.id_cuadrilla = :id_cuadrilla
                  order by em.apellido asc, em.nombre asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_cuadrilla', $id_cuadrilla);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



    function save(){ //ok
        if($this->id_cuadrilla_empleado)
        {$rta = $this->updateCuadrillaEmpleado();}
        else
        {$rta =$this->insertCuadrillaEmpleado();}
        return $rta;
    }


    public function updateCuadrillaEmpleado(){ //ok
        $stmt=new sQuery();
        $query="update nov_cuadrilla_empleado set id_empleado = :id_empleado
                where id_cuadrilla_empleado = :id_cuadrilla_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado', $this->getIdEmpleado());
        $stmt->dpBind(':id_cuadrilla_empleado', $this->getIdCuadrillaEmpleado());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertCuadrillaEmpleado(){
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