<?php

class Postulante
{
    private $id_postulante;
    private $fecha;
    private $apellido;
    private $nombre;
    private $dni;
    private $lista_negra;
    private $telefono;
    private $formacion;
    private $id_especialidad;
    private $id_localidad;
    private $comentarios;


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

    function getTelefono()
    { return $this->telefono;}

    function getFormacion()
    { return $this->formacion;}

    function getIdEspecialidad()
    { return $this->id_especialidad;}

    function getIdLocalidad()
    { return $this->id_localidad;}

    function getComentarios()
    { return $this->comentarios;}


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

    function setTelefono($val)
    { $this->telefono=$val;}

    function setFormacion($val)
    { $this->formacion=$val;}

    function setIdEspecialidad($val)
    { $this->id_especialidad=$val;}

    function setIdLocalidad($val)
    { $this->id_localidad=$val;}

    function setComentarios($val)
    { $this->comentarios=$val;}


    function __construct($nro=0){ //constructor //ok

        if ($nro!=0){
            $stmt=new sQuery();
            $query = "select id_postulante,
                      DATE_FORMAT(fecha, '%d/%m/%Y') as fecha,
                      apellido, nombre, dni, lista_negra,
                      telefono, formacion, id_especialidad, id_localidad, comentarios
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
            $this->setTelefono($rows[0]['telefono']);
            $this->setFormacion($rows[0]['formacion']);
            $this->setIdEspecialidad($rows[0]['id_especialidad']);
            $this->setIdLocalidad($rows[0]['id_localidad']);
            $this->setComentarios($rows[0]['comentarios']);
        }
    }


    public static function getPostulantes($id_localidad, $id_especialidad) { //ok
        $stmt=new sQuery();
        $query = "select pos.id_postulante,
                  DATE_FORMAT(pos.fecha,  '%d/%m/%Y') as fecha,
                  pos.apellido, pos.nombre, pos.dni, pos.lista_negra, pos.telefono, pos.formacion, pos.id_localidad, pos.id_especialidad,
                  loc.ciudad, loc.CP, loc.provincia,
                  es.nombre as especialidad,
                  (select count(*) from uploads_postulante where id_postulante = pos.id_postulante) as cant_uploads
                  from sel_postulantes pos
                  left join localidades loc on loc.id_localidad = pos.id_localidad
                  left join sel_especialidades es on es.id_especialidad = pos.id_especialidad
                  where if(:id_especialidad is not null, pos.id_especialidad = :id_especialidad, 1)
                  and if(:id_localidad is not null, pos.id_localidad = :id_localidad, 1)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_localidad', $id_localidad);
        $stmt->dpBind(':id_especialidad', $id_especialidad);
        //$stmt->dpBind(':renovado', $renovado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public static function getPostulantesActivos() { //ok
        //se usa para cargar combo de postulantes en formulario de postulacion
        $stmt=new sQuery();
        $query = "select *
                  from sel_postulantes pos
                  order by pos.apellido asc, pos.nombre asc";

        $stmt->dpPrepare($query);
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
                lista_negra = :lista_negra,
                telefono = :telefono,
                formacion = :formacion,
                id_especialidad = :id_especialidad,
                id_localidad = :id_localidad,
                comentarios = :comentarios
                where id_postulante = :id_postulante";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':dni', $this->getDni());
        $stmt->dpBind(':lista_negra', $this->getListaNegra());
        $stmt->dpBind(':telefono', $this->getTelefono());
        $stmt->dpBind(':formacion', $this->getFormacion());
        $stmt->dpBind(':id_especialidad', $this->getIdEspecialidad());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpBind(':id_postulante', $this->getIdPostulante());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    private function insertPostulante(){ //ok
        $stmt=new sQuery();
        $query="insert into sel_postulantes(fecha, apellido, nombre, dni, lista_negra, telefono, formacion, id_especialidad, id_localidad, comentarios)
                values(sysdate(), :apellido, :nombre, :dni, :lista_negra, :telefono, :formacion, :id_especialidad, :id_localidad, :comentarios)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':dni', $this->getDni());
        $stmt->dpBind(':lista_negra', $this->getListaNegra());
        $stmt->dpBind(':telefono', $this->getTelefono());
        $stmt->dpBind(':formacion', $this->getFormacion());
        $stmt->dpBind(':id_especialidad', $this->getIdEspecialidad());
        $stmt->dpBind(':id_localidad', $this->getIdLocalidad());
        $stmt->dpBind(':comentarios', $this->getComentarios());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    function deletePostulante(){ //ok
        $stmt=new sQuery();
        $query="delete from sel_postulantes
                where id_postulante =:id_postulante";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_postulante', $this->getIdPostulante());
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


    public function checkDni($dni, $id_postulante) { //ok
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