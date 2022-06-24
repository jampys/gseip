<?php

class UsuarioRol
{
    private $id_user_role;
    private $id_user;
    private $id_role;
    private $fecha_desde;
    private $fecha_hasta;
    private $created_by;
    private $created_date;

    //GETTERS
    function getIdUserRole()
    { return $this->id_user_role;}

    function getIdUser()
    { return $this->id_user;}

    function getIdRole()
    { return $this->id_role;}

    function getFechaDesde()
    { return $this->fecha_desde;}

    function getFechaHasta()
    { return $this->fecha_hasta;}

    function getCreatedBy()
    { return $this->created_by;}

    function getCreatedDate()
    { return $this->created_date;}


    //SETTERS
    function setIdUserRole($val)
    { $this->id_user_role=$val;}

    function setIdUser($val)
    { $this->id_user=$val;}

    function setIdRole($val)
    {  $this->id_role=$val;}

    function setFechaDesde($val)
    {  $this->fecha_desde=$val;}

    function setFechaHasta($val)
    {  $this->fecha_hasta=$val;}

    function setCreatedBy($val)
    {  $this->created_by=$val;}

    function setCreatedDate($val)
    {  $this->created_date=$val;}


    function __construct($nro=0){ //constructor //ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select ur.id_user_role, ur.id_user, ur.id_role,
                    DATE_FORMAT(ur.fecha_desde,  '%d/%m/%Y') as fecha_desde,
                    DATE_FORMAT(ur.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
                    DATE_FORMAT(ur.created_date, '%d/%m/%Y %H:%i') as created_date,
                    ur.created_by
                    from sec_user_role ur where id_user_role = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdUserRole($rows[0]['id_user_role']);
            $this->setIdUser($rows[0]['id_user']);
            $this->setIdRole($rows[0]['id_role']);
            $this->setFechaDesde($rows[0]['fecha_desde']);
            $this->setFechaHasta($rows[0]['fecha_hasta']);
            $this->setCreatedBy($rows[0]['created_by']);
            $this->setCreatedDate($rows[0]['created_date']);
        }
    }

    //Devuelve todos los roles de un determinado usuario
    public static function getRoles($id_user) { //ok
        $stmt=new sQuery();
        $query = "select sur.id_user_role, sur.id_user, sur.id_role,
DATE_FORMAT(sur.fecha_desde,  '%d/%m/%Y') as fecha_desde,
DATE_FORMAT(sur.fecha_hasta,  '%d/%m/%Y') as fecha_hasta,
sr.nombre,
DATE_FORMAT(sur.created_date, '%d/%m/%Y %H:%i') as created_date,
us.user userc
from sec_user_role sur
join sec_roles sr on sr.id_role = sur.id_role
join sec_users us on us.id_user = sur.created_by
where sur.id_user = :id_user";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $id_user);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();

    }


    function save(){ //ok
        if($this->id_user_role)
        {$rta = $this->updateUserRole();}
        else
        {$rta =$this->insertUserRole();}
        return $rta;
    }



    public function updateUserRole(){ //ok

        $stmt=new sQuery();
        $query="update sec_user_role
                set fecha_hasta = STR_TO_DATE(:fecha_hasta, '%d/%m/%Y')
                where id_user_role = :id_user_role";
        $stmt->dpPrepare($query);
        //$stmt->dpBind(':id_role', $this->getIdRole());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':id_user_role', $this->getIdUserRole());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();

    }

    public function insertUserRole(){ //ok

        $stmt=new sQuery();
        $query="insert into sec_user_role(id_user, id_role, fecha_desde, fecha_hasta, created_by, created_date)
                values(:id_user, :id_role,
                sysdate(),
                STR_TO_DATE(:fecha_hasta, '%d/%m/%Y'), :created_by, sysdate())";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $this->getIdUser());
        $stmt->dpBind(':id_role', $this->getIdRole());
        $stmt->dpBind(':fecha_hasta', $this->getFechaHasta());
        $stmt->dpBind(':created_by', $this->getCreatedBy());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }

    public function deleteUserRole(){ //ok
        $stmt=new sQuery();
        $query="delete from sec_user_role where id_user_role = :id_user_role";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user_role', $this->getIdUserRole());
        $stmt->dpExecute();
        return $stmt->dpGetAffect();
    }


    public function checkVehiculo($id_vehiculo, $id_contrato, $id_contrato_vehiculo) {
        //verifica que el vehiculo no se repita dentro de un contrato
        $stmt=new sQuery();
        $query = "select 1
from vto_vehiculo_contrato vvc
where vvc.id_vehiculo = :id_vehiculo
and vvc.id_contrato = :id_contrato
and vvc.id_vehiculo_contrato <> :id_contrato_vehiculo";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_vehiculo', $id_vehiculo);
        $stmt->dpBind(':id_contrato', $id_contrato);
        $stmt->dpBind(':id_contrato_vehiculo', $id_contrato_vehiculo);
        $stmt->dpExecute();
        return $output = ($stmt->dpGetAffect()==0)? true : false;
    }


}

?>