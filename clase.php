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
            $conect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
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




class Cliente
{
	private $nombre;     //se declaran los atributos de la clase, que son los atributos del cliente
	private $apellido;
	private $fecha;
	private $peso;
	private $id;

    public static function getClientes() {
			$stmt=new sQuery();
            $stmt->dpPrepare("select id, nombre, apellido, DATE_FORMAT(fecha_nac,  '%d/%m/%Y') as fecha_nac, peso from clientes");
            $stmt->dpExecute();
            return $stmt->dpFetchAll(); // retorna todos los clientes
		}

	function Cliente($nro=0){ // declara el constructor, si trae el numero de cliente lo busca , si no, trae todos los clientes

		if ($nro!=0){

            $stmt=new sQuery();
            $query="select id, nombre, apellido, DATE_FORMAT(fecha_nac,  '%d/%m/%Y') as fecha_nac, peso from clientes where id = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setID($rows[0]['id']);
            $this->setNombre($rows[0]['nombre']);
            $this->setApellido($rows[0]['apellido']);
            $this->setFecha($rows[0]['fecha_nac']);
            $this->setPeso($rows[0]['peso']);
		}
	}
		
		// metodos que devuelven valores
	function getID()
	 { return $this->id;}
	function getNombre()
	 { return $this->nombre;}
	function getApellido()
	 { return $this->apellido;}
	function getFecha()
	 { return $this->fecha;}
	function getPeso()
	 { return $this->peso;}
	 
		// metodos que setean los valores
    function setID($val)
    { $this->id=$val;}
	function setNombre($val)
	 { $this->nombre=$val;}
	function setApellido($val)
	 {  $this->apellido=$val;}
	function setFecha($val)
	 {  $this->fecha=$val;}
	function setPeso($val)
	 {  $this->peso=$val;}

    function save(){
        if($this->id)
        {$rta = $this->updateCliente();}
        else
        {$rta =$this->insertCliente();}
        return $rta;
    }

	public function updateCliente(){	// actualiza el cliente cargado en los atributos

        $stmt=new sQuery();
        $query="update clientes set nombre= :nombre, apellido= :apellido, fecha_nac= STR_TO_DATE(:fecha, '%d/%m/%Y'), peso= :peso where id = :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':fecha', $this->getFecha());
        $stmt->dpBind(':peso', $this->getPeso());
        $stmt->dpBind(':id', $this->getID());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	private function insertCliente(){	// inserta el cliente cargado en los atributos

        $stmt=new sQuery();
        $query="insert into clientes( nombre, apellido, fecha_nac,peso)values(:nombre, :apellido, STR_TO_DATE(:fecha, '%d/%m/%Y'), :peso)";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':apellido', $this->getApellido());
        $stmt->dpBind(':fecha', $this->getFecha());
        $stmt->dpBind(':peso', $this->getPeso());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}

	function delete(){	// elimina el cliente
        $stmt=new sQuery();
        $query="delete from clientes where id= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getID());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
	}	
	
}
function cleanString($string)
{
    $string=trim($string);
    $string=mysql_escape_string($string);
	$string=htmlspecialchars($string);
	
    return $string;
}