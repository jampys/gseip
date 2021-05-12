<?php
class NoConformidad
{
    private $id_no_conformidad;
    private $nombre;
    private $tipo;
    private $analisis_causa;
    private $tipo_accion;
    private $descripcion;
    private $accion_inmediata;
    private $analisis_causa_desc;
    private $id_responsable_seguimiento;

    // GETTERS
    function getIdNoConformidad()
    { return $this->id_no_conformidad;}

    function getNombre()
    { return $this->nombre;}

    function getTipo()
    { return $this->tipo;}

    function getAnalisisCausa()
    { return $this->analisis_causa;}

    function getTipoAccion()
    { return $this->tipo_accion;}

    function getDescripcion()
    { return $this->descripcion;}

    function getAccionInmediata()
    { return $this->accion_inmediata;}

    function getAnalisisCausaDesc()
    { return $this->analisis_causa_desc;}

    function getIdResponsableSeguimiento()
    { return $this->id_responsable_seguimiento;}

    //SETTERS
    function setIdNoConformidad($val)
    { $this->id_no_conformidad=$val;}

    function setNombre($val)
    { $this->nombre=$val;}

    function setTipo($val)
    { $this->tipo=$val;}

    function setAnalisisCausa($val)
    {  $this->analisis_causa=$val;}

    function setTipoAccion($val)
    {  $this->tipo_accion=$val;}

    function setDescripcion($val)
    { $this->descripcion=$val;}

    function setAccionInmediata($val)
    {  $this->accion_inmediata=$val;}

    function setAnalisisCausaDesc($val)
    {  $this->analisis_causa_desc=$val;}

    function setIdResponsableSeguimiento($val)
    {  $this->id_responsable_seguimiento=$val;}


    public static function getNoConformidades() {
        $stmt=new sQuery();
        $query="select *
                from no_no_conformidad";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from no_no_conformidad where id_no_conformidad = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdNoConformidad($rows[0]['id_no_conformidad']);
            $this->setNombre($rows[0]['nombre']);
            $this->setTipo($rows[0]['tipo']);
            $this->setAnalisisCausa($rows[0]['analisis_causa']);
            $this->setTipoAccion($rows[0]['tipo_accion']);
            $this->setDescripcion($rows[0]['descripcion']);
            $this->setAccionInmediata($rows[0]['accion_inmediata']);
            $this->setAnalisisCausaDesc($rows[0]['analisis_causa_desc']);
            $this->setIdResponsableSeguimiento($rows[0]['id_responsable_seguimiento']);
        }
    }



    function save(){
        if($this->id_puesto)
        {$rta = $this->updatePuesto();}
        else
        {$rta =$this->insertPuesto();}
        return $rta;
    }

    public function updatePuesto(){

        $stmt=new sQuery();
        $query="update puestos set
                nombre= :nombre,
                descripcion= :descripcion,
                codigo= :codigo,
                id_puesto_superior= :id_puesto_superior,
                id_area= :id_area,
                id_nivel_competencia= :id_nivel_competencia
                where id_puesto = :id_puesto";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':codigo', $this->getCodigo());
        $stmt->dpBind(':id_puesto_superior', $this->getIdPuestoSuperior());
        $stmt->dpBind(':id_area', $this->getIdArea());
        $stmt->dpBind(':id_nivel_competencia', $this->getIdNivelCompetencia());
        $stmt->dpBind(':id_puesto', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    private function insertPuesto(){

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


    public function autocompletarPuestos($term) {
        $stmt=new sQuery();
        $query = "select *
                  from puestos
                  where nombre like '%$term%'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }



}

?>