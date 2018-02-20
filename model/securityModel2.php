<?php


class Privilege
{
    protected $actions;

    protected function __construct() {
        $this->actions = array();
    }


    public static function getPrivilegeActions($privilege_id) { //obtiene todas las acciones de un privilegio
        $privilege = new Privilege();

        $stmt=new sQuery();
        $query="select *
                from sec_privilege_action spa, sec_actions sa
                where spa.id_action = sa.id_action
                and spa.id_privilege = :id_privilege";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_privilege', $privilege_id);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();


        foreach($rows as $row) {
            $privilege->actions[$row["code"]] = true;
        }
        return $privilege;
    }


    public function hasAction($Action) { // check if a permission is set
        return isset($this->actions[$Action]);
    }
}

class Role
{
    protected $privileges;

    protected function __construct() {
        $this->privileges = array();
    }


    public static function getRolePrivileges($role_id, $id_object) { //obtengo todos los privilegios del rol
        $role = new Role();

        $stmt=new sQuery();
        $query = "select * -- sp.code, srp.id_privilege
                from sec_role_privilege srp, sec_privileges sp
                where srp.id_privilege = sp.id_privilege
                and srp.id_role = :id_role
                and srp.is_allowed = 1
                -- seguridad domain --
                and (1 in (select id_domain   -- 1 (dominio accesible por todos) = dominio del objeto
			              from sec_domain_object
			              where id_object = :id_object)
                    or 1 = srp.id_domain -- 1 (dominio accesible por todos) = dominio del privilegio
                    or srp.id_domain in (select id_domain -- srp.id_domain (dominio del privilegio) = dominio del objeto
			                              from sec_domain_object
			                              where id_object = :id_object)
                    or :id_object = 0
                    )";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_role', $role_id);
        $stmt->dpBind(':id_object', $id_object);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();


        foreach($rows as $row) {
            //$role->permissions[$row["code"]] = true;
            $role->privileges[$row["code"]] = Privilege::getPrivilegeActions($row["id_privilege"]);
        }
        return $role;
    }


    public function hasPrivilege($privilege) { // check if a permission is set
        return isset($this->privileges[$privilege]);
    }

    public function hasAction($action) {
        foreach ($this->privileges as $privilege) {
            if ($privilege->hasAction($action)) {
                return true;
            }
        }
        return false;
    }
}




class PrivilegedUser
{
    private $roles;
    private $id_user;
    //private $id_domain;

    public function __construct($id_user) {
        $this->id_user = $id_user;
        //$this->id_domain = $id_domain;
        //$this->initRoles();
    }

    public static function dhasPrivilege($privilege, $domains){
        $obj = unserialize($_SESSION['loggedUser'])->hasPrivilege($privilege, $domains);
        return $obj;
    }

    public static function dhasAction($action, $domains){
        $obj = unserialize($_SESSION['loggedUser'])->hasAction($action, $domains);
        return $obj;
    }


    protected function initRoles($id_object) { // populate roles with their associated permissions

        $stmt=new sQuery();
        $query="select id_user, id_role
                from sec_user_role sur
                where id_user = :id_user";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $this->id_user);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();

        foreach($rows as $row) {
            $this->roles[$row["role_name"]] = Role::getRolePrivileges($row["id_role"], $id_object );
        }
    }


    public function hasPrivilege($privilege, $id_object) { // check if user has a specific privilege
        $this->initRoles($id_object);
        foreach ($this->roles as $role) {
            if ($role->hasPrivilege($privilege)) {
                return true;
            }
        }
        return false;
    }



    public function hasAction($action, $id_object) { //chequea si el usuario tiene una accion especifica
        $this->initRoles($id_object);
        foreach ($this->roles as $role) {
            if ($role->hasAction($action)) {
                return true;
            }
        }
        return false;
    }


}




?>