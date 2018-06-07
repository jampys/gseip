<?php

class Postulante
{
    private $id_postulante;
    private $fecha;
    private $apellido;
    private $nombre;
    private $dni;
    private $lista_negra;

    // GETTERS
    function getIdPostulante()
    { return $this->id_postulante;}

    function getFecha()
    { return $this->fecha;}

    function getApellido()
    { return $this->apellido;}

    function getNombre()
    { return $this->nombre;}

    function getDni()
    { return $this->dni;}

    function getListaNegra()
    { return $this->lista_negra;}


    //SETTERS
    function setIdPostulante($val)
    { $this->id_postulante=$val;}

    function setFecha($val)
    {  $this->fecha=$val;}

    function setApellido($val)
    { $this->apellido=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setDni($val)
    { $this->dni=$val;}

    function setListaNegra($val)
    { $this->lista_negra=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_postulante,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      apellido, nombre, dni, lista_negra
                      from sel_postulantes
                      where id_postulante = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdPostulante($rows[0]['id_postulante']);
            $this->setFecha($rows[0]['fecha']);
            $this->setApellido($rows[0]['apellido']);
            $this->setNombre($rows[0]['nombre']);
            $this->setDni($rows[0]['dni']);
            $this->setListaNegra($rows[0]['lista_negra']);
        }
    }


    public static function getPostulantes($id_puesto, $id_localidad, $id_contrato, $todas) { //ok
        $stmt=new sQuery();
        /*$query = "select bu.id_busqueda,
                  DATE_FORMAT(bu.fecha,  '%d/%m/%Y') as fecha,
                  bu.nombre,
                  DATE_FORMAT(bu.fecha_apertura,  '%d/%m/%Y') as fecha_apertura,
                  DATE_FORMAT(bu.fecha_cierre,  '%d/%m/%Y') as fecha_cierre,
                  pu.nombre as puesto,
                  loc.ciudad as area,
                  co. nombre as contrato,
                  bu.estado
                  from sel_busquedas bu
                  left join puestos pu on bu.id_puesto = pu.id_puesto
                  left join localidades loc on bu.id_localidad = loc.id_localidad
                  left join contratos co on bu.id_contrato = co.id_contrato";*/
        $query = "select *
                  from sel_postulantes pos";

        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_empleado', $id_empleado);
        //$stmt->dpBind(':id_grupo', $id_grupo);
        //$stmt->dpBind(':id_vencimiento', $id_vencimiento);
        //$stmt->dpBind(':id_contrato', $id_contrato);
        //$stmt->dpBind(':renovado', $renovado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function save(){ //ok
        if($this->id_postulante)
        {$rta = $this->updatePostulante();}
        else
        {$rta =$this->insertPostulante();}
        return $rta;
    }


    public function updatePostulante(){ //ok
        $stmt=new sQuery();
        $query="update sel_postulantes set apellido = :apellido,
                nombre =:nombre,
                dni = :dni,
                lista_negra = :lista_negra
                where id_postulante = :id_postulante";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':dni', $this->getDni());
        $stmt->dpBind(':lista_negra', $this->getListaNegra());
        $stmt->dpBind(':id_postulante', $this->getIdPostulante());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertPostulante(){ //ok
        $stmt=new sQuery();
        $query="insert into sel_postulantes(fecha, apellido, nombre, dni, lista_negra)
                values(sysdate(), :apellido, :nombre, :dni, :lista_negra)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':dni', $this->getDni());
        $stmt->dpBind(':lista_negra', $this->getListaNegra());
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



    public static function uploadsUpload($directory, $name, $id_postulante){ //ok
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



    public static function uploadsLoad($id_postulante) { //ok
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

    public static function uploadsDelete($name){ //ok
        $stmt=new sQuery();
        $query="delete from uploads_postulante where name =:name";
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