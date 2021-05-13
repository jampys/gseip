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


    public static function getNoConformidades($id_empleado, $id_grupo, $id_vencimiento, $id_contrato, $id_subcontratista, $renovado){
        $stmt=new sQuery();
        $query="select nc.id_no_conformidad, nc.nombre, nc.tipo, nc.analisis_causa, nc.tipo_accion,
nc.descripcion, nc.accion_inmediata, nc.analisis_causa_desc,
DATE_FORMAT(nc.created_date,  '%d/%m/%Y') as created_date
from nc_no_conformidad nc
join empleados em on nc.id_responsable_seguimiento = em.id_empleado";

        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    function __construct($nro=0){ //constructor

        if ($nro!=0){

            $stmt=new sQuery();
            $query="select * from nc_no_conformidad where id_no_conformidad = :nro";
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



    function save(){ //ok
        if($this->id_no_conformidad)
        {$rta = $this->updateNoConformidad();}
        else
        {$rta =$this->insertNoConformidad();}
        return $rta;
    }

    public function updateNoConformidad(){

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

    private function insertNoConformidad(){ //ok

        $stmt=new sQuery();
        $query="insert into nc_no_conformidad(nombre, tipo, analisis_causa, tipo_accion, descripcion, accion_inmediata, analisis_causa_desc, id_responsable_seguimiento, created_date)
                values(:nombre, :tipo, :analisis_causa, :tipo_accion, :descripcion, :accion_inmediata, :analisis_causa_desc, :id_responsable_seguimiento, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':nombre', $this->getNombre());
        $stmt->dpBind(':tipo', $this->getTipo());
        $stmt->dpBind(':analisis_causa', $this->getAnalisisCausa());
        $stmt->dpBind(':tipo_accion', $this->getTipoAccion());
        $stmt->dpBind(':descripcion', $this->getDescripcion());
        $stmt->dpBind(':accion_inmediata', $this->getAccionInmediata());
        $stmt->dpBind(':analisis_causa_desc', $this->getAnalisisCausaDesc());
        $stmt->dpBind(':id_responsable_seguimiento', $this->getIdResponsableSeguimiento());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    function deleteNoConformidad(){
        $stmt=new sQuery();
        $query="delete from puestos where id_puesto= :id";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id', $this->getIdPuesto());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }



}

?>