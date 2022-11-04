<?php

class Postulacion
{
    private $id_postulacion;
    private $id_busqueda;
    private $fecha; //fecha de registro en sistema
    private $id_postulante;
    private $origen_cv;
    private $expectativas;
    private $propuesta_economica;
    private $id_user;

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

    function getIdUser()
    { return $this->id_user;}


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

    function setIdUser($val)
    { $this->id_user=$val;}


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
                  (select sex.etapa from sel_etapas sex where sex.id_postulacion = pos.id_postulacion order by sex.fecha_etapa desc limit 1  ) as etapa,
                  (select concat(u.directory, u.name) from uploads_postulante u where u.id_postulante = pos.id_postulante limit 1) as cv,
                  pos.id_postulacion, pos.id_busqueda, pos.id_postulante,
                  DATE_FORMAT(pos.fecha,  '%d/%m/%Y %H:%i') as fecha,
                  pos.origen_cv, pos.expectativas, pos.propuesta_economica,
                  CONCAT(po.apellido, ' ', po.nombre) as postulante,
                  bu.nombre as busqueda,
                  loc.ciudad,
                  us.user, us.id_user
                  from sel_postulaciones pos
                  join sel_postulantes po on pos.id_postulante = po.id_postulante
                  join sel_busquedas bu on pos.id_busqueda = bu.id_busqueda
                  join sec_users us on us.id_user = pos.id_user
                  left join localidades loc on loc.id_localidad = bu.id_localidad
                  where pos.id_busqueda =  ifnull(:id_busqueda, pos.id_busqueda)
                  and pos.id_postulante =  ifnull(:id_postulante, pos.id_postulante)
                  order by aplica desc, postulante asc";

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
        $query="insert into sel_postulaciones(id_busqueda, id_postulante, fecha, origen_cv, expectativas, propuesta_economica, id_user)
                values(:id_busqueda, :id_postulante, sysdate(), :origen_cv, :expectativas, :propuesta_economica, :id_user)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_busqueda', $this->getIdBusqueda());
        $stmt->dpBind(':id_postulante', $this->getIdPostulante());
        $stmt->dpBind(':origen_cv', $this->getOrigenCv());
        $stmt->dpBind(':expectativas', $this->getExpectativas());
        $stmt->dpBind(':propuesta_economica', $this->getPropuestaEconomica());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deletePostulacion(){ //ok
        $stmt=new sQuery();
        $query="delete from sel_postulaciones where id_postulacion =:id_postulacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulacion', $this->getIdPostulacion());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkPostulacion($id_postulante, $id_busqueda, $id_postulacion) { //ok
        //verifica que el postulante no se repita dentro de una busqueda
        $stmt=new sQuery();
        $query = "select 1
from sel_postulaciones p
where p.id_postulante = :id_postulante
and p.id_busqueda = :id_busqueda
and p.id_postulacion <> :id_postulacion";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulante', $id_postulante);
        $stmt->dpBind(':id_busqueda', $id_busqueda);
        $stmt->dpBind(':id_postulacion', $id_postulacion);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
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




}




?>