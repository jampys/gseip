<?php

class Usuario{

    private $id_user;
    private $user;
    private $password;
    private $enabled;
    private $fecha_alta;
    private $fecha_baja;
    private $id_empleado;
    private $profile_picture;


    // metodos que devuelven valores
    function getIdUser()
    { return $this->id_user;}

    function getUser()
    { return $this->user;}

    function getPassword()
    { return $this->password;}

    function getEnabled()
    { return $this->enabled;}

    function getFechaAlta()
    { return $this->fecha_alta;}

    function getFechaBaja()
    { return $this->fecha_baja;}

    function getIdEmpleado()
    { return $this->id_empleado;}

    function getProfilePicture()
    { return $this->profile_picture;}

    // metodos que setean los valores
    function setIdUser($val)
    { $this->id_user=$val;}

    function setUser($val)
    {  $this->user=$val;}

    function setPassword($val)
    {  $this->password=$val;}

    function setEnabled($val)
    {  $this->enabled=$val;}

    function setFechaAlta($val)
    {  $this->fecha_alta=$val;}

    function setFechaBaja($val)
    {  $this->fecha_baja=$val;}

    function setIdEmpleado($val)
    {  $this->id_empleado=$val;}

    function setProfilePicture($val)
    {  $this->profile_picture=$val;}


    public static function getUsuarios() { //ok
        $stmt=new sQuery();
        $query="select su.id_user, su.user, su.enabled,
DATE_FORMAT(su.fecha_alta,  '%d/%m/%Y') as fecha_alta,
DATE_FORMAT(su.fecha_baja,  '%d/%m/%Y') as fecha_baja,
em.apellido, em.nombre
from sec_users su
join empleados em on su.id_empleado = em.id_empleado";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor ok

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select us.id_user, us.user, us.password, us.enabled,
                    DATE_FORMAT(us.fecha_alta,  '%d/%m/%Y') as fecha_alta,
                    DATE_FORMAT(us.fecha_baja,  '%d/%m/%Y') as fecha_baja,
                    us.id_empleado, us.profile_picture
                    from sec_users us where us.id_user = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdUser($rows[0]['id_user']);
            $this->setUser($rows[0]['user']);
            $this->setPassword($rows[0]['password']);
            $this->setEnabled($rows[0]['enabled']);
            $this->setFechaAlta($rows[0]['fecha_alta']);
            $this->setFechaBaja($rows[0]['fecha_baja']);
            $this->setIdEmpleado($rows[0]['id_empleado']);
        }
    }


    function isAValidUser($usuario,$password){ //ok

        $stmt=new sQuery();
        $query="select * from sec_users where user = :usuario and password = md5(:password)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':usuario', $usuario);
        $stmt->dpBind(':password', $password);
        $stmt->dpExecute();
        //return $stmt->dpGetAffect();
        $r=$stmt->dpFetchAll();

        if ($stmt->dpGetAffect()>=1) //en teoria devuelve la cantidad de filas afectadas. OJO controlar esta funcion
        {
            //lo trabajo de la manera porque a pesar de ser un solo registro el de la consulta
            // lo devuelve en forma de un array bidimensional
            //$datos=array();
            if($r[0]['enabled']==1){
                //$datos[0] =(int )$r[0]['id_usuario'];
                //$datos[1] = $r[0]['usuario'];
                //return $datos;
                $this->setIdUser($r[0]['id_user']);
                $this->setUser($r[0]['user']);
                $this->setIdEmpleado($r[0]['id_empleado']);
                $this->setProfilePicture($r[0]['profile_picture']);

                return 1;
            }

            else return 0;
        }
        else
        { return -1;
        }
    }



    function checkUserExists($usuario){ //ok

        $stmt=new sQuery();
        $query="select * from sec_users where user = :usuario";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':usuario', $usuario);
        //$stmt->dpBind(':password', $password);
        $stmt->dpExecute();
        //return $stmt->dpGetAffect();
        $r=$stmt->dpFetchAll();

        if ($stmt->dpGetAffect()>=1) //en teoria devuelve la cantidad de filas afectadas. OJO controlar esta funcion
        {
            //lo trabajo de la manera porque a pesar de ser un solo registro el de la consulta
            // lo devuelve en forma de un array bidimensional
            //$datos=array();
            if($r[0]['enabled']==1){
                $this->setIdUser($r[0]['id_user']);
                $this->setUser($r[0]['user']);
                //$this->setIdEmpleado($r[0]['id_empleado']);
                //$this->setProfilePicture($r[0]['profile_picture']);

                return 1;
            }

            else return 0;
        }
        else
        { return -1;
        }
    }



    public function updateCode($code){ //ok

        $stmt=new sQuery();
        $query="update sec_users set
                reset_code = :reset_code
                where id_user = :id_user";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpBind(':reset_code', $code);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




    function checkCode($code){ //ok

        $stmt=new sQuery();
        $query="select * from sec_users where id_user = :usuario and reset_code = :reset_code";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':usuario', $this->getIdUser());
        $stmt->dpBind(':reset_code', $code);
        $stmt->dpExecute();
        //return $stmt->dpGetAffect();
        //$r=$stmt->dpFetchAll();

        if ($stmt->dpGetAffect()>=1) return 1; //en teoria devuelve la cantidad de filas afectadas. OJO controlar esta funcion
        else return -1;

    }


    public function updatePassword(){ //ok

        $stmt=new sQuery();
        $query="update sec_users set
                password= md5(:password),
                last_reset_date = SYSDATE()
                where id_user = :id_user";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpBind(':password', $this->getPassword());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



    /* para ABM *****************************************************************************/

    function save(){ //ok
        if($this->id_user)
        {$rta = $this->updateUsuario();}
        else
        {$rta =$this->insertUsuario();}
        return $rta;
    }

    public function updateUsuario(){ //ok

        $stmt=new sQuery();
        $query="update sec_users set
                user = :user
                where id_user = :id_user";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':user', $this->getUser());
        //$stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertUsuario(){

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

    function deletePuesto(){
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public static function uploadsUpload($directory, $name, $id_user){ //ok
        $stmt=new sQuery();
        /*$query="insert into uploads_puesto(directory, name, fecha, id_puesto)
                values(:directory, :name, sysdate(), :id_puesto)";*/
        $query="update sec_users set profile_picture = :profile_picture
                where id_user = :id_user";

        $stmt->dpPrepare($query);
        //$stmt->dpBind(':directory', $directory);
        $stmt->dpBind(':profile_picture', $directory.$name);
        $stmt->dpBind(':id_user', $id_user);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public static function uploadsLoad($id_user) { //ok
        //select id_upload, directory, name, DATE_FORMAT(fecha,'%d/%m/%Y') as fecha, id_puesto
        $stmt=new sQuery();
        $query = "select id_user, profile_picture, DATE_FORMAT(fecha_alta,'%d/%m/%Y') as fecha
                  from sec_users
                  where id_user = :id_user";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $id_user);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }

    public static function uploadsDelete($id_user){ //ok
        $stmt=new sQuery();
        $query="update sec_users set profile_picture = 'uploads/profile_pictures/default.png' where id_user = :id_user";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $id_user);
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }




}