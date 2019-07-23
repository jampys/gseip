<?php

class Usuario{

    private $id_user;
    private $user;
    private $password;
    private $id_empleado;
    private $profile_picture;


    // metodos que devuelven valores
    function getIdUser()
    { return $this->id_user;}

    function getUser()
    { return $this->user;}

    function getPassword()
    { return $this->password;}

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

    function setIdEmpleado($val)
    {  $this->id_empleado=$val;}

    function setProfilePicture($val)
    {  $this->profile_picture=$val;}


    function isAValidUser($usuario,$password){

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



    function checkUserExists($usuario){

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



    public function updateCode($code){

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



}