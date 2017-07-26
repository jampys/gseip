<?php


class Localidad
{
	private $nombre;
	private $apellido;
	private $fecha;
	private $peso;
	private $id;

    public static function getLocalidades() {
			$stmt=new sQuery();
            $stmt->dpPrepare("select * from localidades");
            $stmt->dpExecute();
            return $stmt->dpFetchAll(); // retorna todas las localidades
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








	
}
