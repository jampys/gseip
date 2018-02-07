<?php


class Privilege
{
    protected $actions;

    protected function __construct() {
        $this->actions = array();
    }


    public static function getPrivilegeActions($privilege_id) { //obtengo todas las acciones de un privilegio
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

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->actions[$permission]);
    }
}

class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }


    public static function getRolePerms($role_id) { //obtengo todos los privilegios del rol
        $role = new Role();

        $stmt=new sQuery();
        $query="select sp.code, srp.id_privilege
                from sec_role_privilege srp, sec_privileges sp
                where srp.id_privilege = sp.id_privilege
                and srp.id_role = :id_role";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_role', $role_id);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();


        foreach($rows as $row) {
            //$role->permissions[$row["code"]] = true;
            $role->permissions[$row["code"]] = Privilege::getPrivilegeActions($row["id_privilege"]);
        }
        return $role;
    }

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
    }

    public function hasNenucos($perm) {
        foreach ($this->permissions as $role) {
            if ($role->hasPerm($perm)) {
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
        //parent::__construct();
        $this->id_user = $id_user;
        $this->initRoles();
    }


    // populate roles with their associated permissions
    protected function initRoles() {

        $stmt=new sQuery();
        $query="select id_user, id_role
                from sec_user_role sur
                where id_user = :id_user";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_user', $this->id_user);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();

        foreach($rows as $row) {
            $this->roles[$row["role_name"]] = Role::getRolePerms($row["id_role"]);
        }
    }

    // check if user has a specific privilege
    public function hasPrivilege($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasPerm($perm)) {
                return true;
            }
        }
        return false;
    }


    /*public function hasAction($perm) {
        foreach ($this->roles as $role) {

            print_r($role);

            foreach ($role as $privilege) {


                if ($privilege->hasPerm($perm)) {
                    return true;
                }

            }

        }
        return false;
    }*/

    public function hasAction($perm) {
        foreach ($this->roles as $role) {
            if ($role->hasNenucos($perm)) {
                return true;
            }
        }
        return false;
    }


}




?>