<?php

class Conexion  // se declara una clase para hacer la conexion con la base de datos
{
    var $con;
    function __construct(){
        // se definen los datos del servidor de base de datos
        $servername = "10.15.34.57"; //host
        $username = "root"; //usuario
        $password = ""; //password
        $dbname = "gestion"; //base de datos

        try{
            //crea la conexion pasandole el servidor , usuario y clave
            $conect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            $conect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con=$conect;
            return $this->con;


        }catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

    }


    function getConexion() // devuelve la conexion
    {
        return $this->con;
    }
    function Close()  // cierra la conexion
    {
        mysql_close($this->con);
    }

}



class sQuery   // se declara una clase para poder ejecutar las consultas, esta clase llama a la clase anterior
{
    var $con;
    var $consulta;
    var $resultados;
    var $st;

    function __construct(){  // constructor, solo crea una conexion usando la clase "Conexion"
        $c = new Conexion();
        $this->con = $c->getConexion();
        $this->st=new PDOStatement();
    }

    /*function executeQuery($cons)  // metodo que ejecuta una consulta y la guarda en el atributo $pconsulta
    {
        $this->consulta= mysql_query($cons,$this->coneccion->getConexion());
        return $this->consulta;
    }*/

    /*function getResults()   // retorna la consulta en forma de result.
    {return $this->consulta;}*/

    function Close()		// cierra la conexion
    {$this->coneccion->Close();}

    function Clean() // libera la consulta
    {mysql_free_result($this->consulta);}

    //function getResultados() // debuelve la cantidad de registros encontrados
    //{return mysql_affected_rows($this->coneccion->getConexion()) ;}

    function dpFetchAll(){
        $rows=array();
        if ($this->st)
        {
            while($row=  $this->st->fetch())
            {
                $rows[]=$row;
            }
        }
        return $rows;
    }


    function dpPrepare($query){
        $this->st = $this->con->prepare($query);
    }

    function dpBind($a, $b){
        $this->st->bindParam($a, $b);
    }

    function dpExecute(){
        $this->st->execute();
    }

    function dpGetAffect(){ // devuelve las cantidad de filas afectadas
        return $this->st->rowCount();
    }



}


$view= new stdClass();

?>