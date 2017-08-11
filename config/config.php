<?php

$GLOBALS['ini'] = parse_ini_file('app.ini');

class Conexion  // se declara una clase para hacer la conexion con la base de datos
{
    var $con;
    function __construct(){
        // se definen los datos del servidor de base de datos
        $servername = $GLOBALS['ini']['db_server'];
        $username = $GLOBALS['ini']['db_user'];
        $password = $GLOBALS['ini']['db_password'];
        $dbname = $GLOBALS['ini']['db_name'];

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


}



class sQuery   // se declara una clase para poder ejecutar las consultas, esta clase llama a la clase anterior
{
    static $con;
    //var $consulta;
    //var $resultados;
    var $st;

    function __construct(){  // constructor, solo crea una conexion usando la clase "Conexion"
        $c = new Conexion();
        self::$con = $c->getConexion();
        $this->st=new PDOStatement();
    }


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
        $this->st = self::$con->prepare($query);
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

    function dpCloseCursor(){ // se usa para los SP
        return $this->st->closeCursor();
    }

    public static function dpBeginTransaction(){ //comenzar una transaccion
        self::$con->beginTransaction();
    }

    public static function dpCommit(){ //commit
        self::$con->commit();
    }

    public static function dpRollback(){ //rollback
        self::$con->rollBack();
    }




}


$view= new stdClass();

?>