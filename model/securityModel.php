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


    public static function getRolePrivileges($role_id) { //obtengo todos los privilegios del rol
        $role = new Role();

        $stmt=new sQuery();
        $query="select sp.code, srp.id_privilege, srp.id_domain
                from sec_role_privilege srp, sec_privileges sp
                where srp.id_privilege = sp.id_privilege
                and srp.id_role = :id_role";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_role', $role_id);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();


        foreach($rows as $row) {
            //$role->permissions[$row["code"]] = true;
            //$role->privileges[$row["code"]] = Privilege::getPrivilegeActions($row["id_privilege"]);
            $role->privileges[$row["code"]] = array('privilege'=>Privilege::getPrivilegeActions($row["id_privilege"]), 'domain'=>$row["id_domain"]);
        }
        return $role;
    }


    public function hasPrivilege($privilege, $object_domain) { // check if a permission is set
        return isset($this->privileges[$privilege]) &&
                                                        ($object_domain == 1
                                                            || $this->privileges[$privilege]['domain'] == $object_domain
                                                            || $this->privileges[$privilege]['domain'] == 1
                                                        );
    }


    public function hasAction($action, $object_domain) {
        foreach ($this->privileges as $privilege) {
            if ($privilege['privilege']->hasAction($action)  &&
                                                                ($object_domain == 1
                                                                    || $privilege['domain'] == $object_domain
                                                                    || $privilege['domain'] == 1
                                                                )) {
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

    public function __construct($id_user) {
        $this->id_user = $id_user;
        $this->initRoles();
    }


    protected function initRoles() { // populate roles with their associated permissions

        $stmt=new sQuery();
        $query="select id_user, id_role
                from sec_user_role sur
                where id_user = :id_user";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $this->id_user);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();

        foreach($rows as $row) {
            $this->roles[$row["role_name"]] = Role::getRolePrivileges($row["id_role"]);
        }
    }


    public function hasPrivilege($privilege, $domain) { // check if user has a specific privilege
        foreach ($this->roles as $role) {
            if ($role->hasPrivilege($privilege, $domain)) {
                return true;
            }
        }
        return false;
    }



    public function hasAction($action, $domain) { //chequea si el usuario tiene una accion especifica
        foreach ($this->roles as $role) {
            if ($role->hasAction($action, $domain)) {
                return true;
            }
        }
        return false;
    }


}




?>