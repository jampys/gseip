<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$GLOBALS['ini'] = parse_ini_file('app.ini');

class Conexion  // se declara una clase para hacer la conexion con la base de datos
{
    private static $con = NULL;

    public static function getInstance() { // Método singleton
        if (is_null(self::$con)) {
            //self::$con = new Conexion();

            // se definen los datos del servidor de base de datos
            $servername = $GLOBALS['ini']['db_server'];
            $username = $GLOBALS['ini']['db_user'];
            $password = $GLOBALS['ini']['db_password'];
            $dbname = $GLOBALS['ini']['db_name'];

            try{
                self::$con = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                if ($_SESSION['id_user']) {self::$con->prepare('set @id_user = '.$_SESSION['id_user'])->execute();}

            }catch(PDOException $e) {
                //echo "Error: " . $e->getMessage();
                //header("Location: index.php?action=error&operation=connection");
                echo '<script type="text/javascript"> window.location.href = "index.php?action=error&operation=connection"; </script>';
                exit;
            }

        }

        return self::$con;
    }


    // Constructor privado, previene la creación de objetos vía new
    private function __construct() { }

    // Clone no permitido
    public function __clone() { }


}



class sQuery   // se declara una clase para poder ejecutar las consultas, esta clase llama a la clase anterior
{
    public static $con;
    var $st;

    function __construct(){  // constructor, solo crea una conexion usando la clase "Conexion"
        self::$con = Conexion::getInstance();
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

    function dpCloseCursor(){ // devuelve las cantidad de filas afectadas
        return $this->st->closeCursor();
    }

    public static function dpBeginTransaction(){
        //new sQuery();
        self::$con = Conexion::getInstance();
        self::$con->beginTransaction();
    }

    public static function dpCommit(){
        self::$con->commit();
    }
	
	public static function dpRollback(){
        self::$con->rollBack();
    }

    function dpGetConnectionId(){ // devuelve el id de la conexion
        return self::$con->query('SELECT CONNECTION_ID()')->fetch(PDO::FETCH_ASSOC);
    }

    public static function dpLastInsertId(){
        return intval(self::$con->lastInsertId());
    }


}



$view= new stdClass();

?>