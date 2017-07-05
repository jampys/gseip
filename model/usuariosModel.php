<?php

class Usuario{

    private $id_usuario;
    private $usuario;
    private $password;


    // metodos que devuelven valores
    function getIdUsuario()
    { return $this->id_usuario;}

    function getUsuario()
    { return $this->usuario;}

    function getPassword()
    { return $this->password;}

    // metodos que setean los valores
    function setIdUsuario($val)
    { $this->id_usuario=$val;}

    function setUsuario($val)
    {  $this->usuario=$val;}

    function setPassword($val)
    {  $this->password=$val;}


    function isAValidUser($usuario,$password){
        /*
        *No olvidar despues de hacer la codificacionn a md5
        *$pass=md5($pass); */


        $stmt=new sQuery();
        $query="select * from sec_usuarios where usuario = :usuario and password = :password";
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
                $this->setIdUsuario(1);//$r[0]['id_usuario']);
                $this->setUsuario($r[0]['usuario']);

                return 1;
            }

            else return 0;
        }
        else
        { return -1;
        }
    }



}