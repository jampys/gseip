<?php
class Puesto
{
    private $id_puesto;
    private $nombre;
    private $descripcion;
    private $codigo;
    private $id_puesto_superior;
    private $id_area;
    private $id_nivel_competencia;

    // GETTERS
    function getIdPuesto()
    { return $this->id_puesto;}

    function getNombre()
    { return $this->nombre;}

    function getDescripcion()
    { return $this->descripcion;}

    function getCodigo()
    { return $this->codigo;}

    function getIdPuestoSuperior()
    { return $this->id_puesto_superior;}

    function getIdArea()
    { return $this->id_area;}

    function getIdNivelCompetencia()
    { return $this->id_nivel_competencia;}

    //SETTERS
    function setIdPuesto($val)
    { $this->id_puesto=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setDescripcion($val)
    { $this->descripcion=$val;}

    function setCodigo($val)
    {  $this->codigo=$val;}

    function setIdPuestoSuperior($val)
    {  $this->id_puesto_superior=$val;}

    function setIdArea($val)
    {  $this->id_area=$val;}

    function setIdNivelCompetencia($val)
    {  $this->id_nivel_competencia=$val;}


    public static function getPuestos() { //ok
        $stmt=new sQuery();
        $query="select pu.id_puesto, pu.nombre, pu.descripcion, pu.codigo,
                    su.nombre as nombre_superior, ar.nombre as area, nc.nombre as nivel_competencia,
                    (select count(*) from uploads_puesto where id_puesto = pu.id_puesto) as cant_uploads
                    from puestos pu
                    left join puestos su on pu.id_puesto_superior = su.id_puesto
                    left join areas ar on pu.id_area = ar.id_area
                    left join competencias_niveles nc on pu.id_nivel_competencia = nc.id_nivel_competencia
                    order by pu.nombre asc";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from puestos where id_puesto = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdPuesto($rows[0]['id_puesto']);
            $this->setNombre($rows[0]['nombre']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setCodigo($rows[0]['codigo']);
            $this->setIdPuestoSuperior($rows[0]['id_puesto_superior']);
            $this->setIdArea($rows[0]['id_area']);
            $this->setIdNivelCompetencia($rows[0]['id_nivel_competencia']);
        }
    }



    function save(){ //ok
        if($this->id_puesto)
        {$rta = $this->updatePuesto();}
        else
        {$rta =$this->insertPuesto();}
        return $rta;
    }

    public function updatePuesto(){ //ok

        $stmt=new sQuery();
        $query="update puestos set
                nombre= :nombre,
                descripcion= :descripcion,
                codigo= :codigo,
                id_puesto_superior= :id_puesto_superior,
                id_area= :id_area,
                id_nivel_competencia= :id_nivel_competencia
                where id_puesto = :id_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':id_puesto_superior', $this->getIdPuestoSuperior());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_nivel_competencia', $this->getIdNivelCompetencia());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertPuesto(){ //ok

        $stmt=new sQuery();
        $query="insert into puestos(nombre, descripcion, codigo, id_puesto_superior, id_area, id_nivel_competencia)
                values(:nombre, :descripcion, :codigo, :id_puesto_superior, :id_area, :id_nivel_competencia)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':id_puesto_superior', $this->getIdPuestoSuperior());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_nivel_competencia', $this->getIdNivelCompetencia());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deletePuesto(){ //ok
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function autocompletarPuestos($term) { //ok
        $stmt=new sQuery();
        $query = "select *
                  from puestos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function uploadsUpload($directory, $name, $id_puesto){ //ok
        $stmt=new sQuery();
        $query="insert into uploads_puesto(directory, name, fecha, id_puesto)
                values(:directory, :name, sysdate(), :id_puesto)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':name', $name);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public static function uploadsLoad($id_puesto) { //ok
        $stmt=new sQuery();
        $query = "select id_upload, directory, name, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, id_puesto
                  from uploads_puesto
                  where id_puesto = :id_puesto
                  order by fecha asc";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function uploadsDelete($name){ //ok
        $stmt=new sQuery();
        $query="delete from uploads_puesto where name =:name";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':name', $name);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public static function getEmpleadosByPuesto($id_puesto) { //ok
        $stmt=new sQuery();
        $query="select em.nombre, em.apellido, concat(co.nro_contrato, ' ', co.nombre) as contrato,
                DATE_FORMAT(ec.fecha_desde, '%d/%m/%Y') as fecha_desde,
                DATE_FORMAT(ec.fecha_hasta, '%d/%m/%Y') as fecha_hasta,
                loc.ciudad, loc.CP
                from empleado_contrato ec
                join contratos co on ec.id_contrato = co.id_contrato
                join empleados em on ec.id_empleado = em.id_empleado
                left join localidades loc  on ec.id_localidad = loc.id_localidad
                where ec.id_puesto = :id_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_puesto', $id_puesto);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


}

?>