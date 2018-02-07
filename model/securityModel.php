<?php

class Role
{
    protected $permissions;

    protected function __construct() {
        $this->permissions = array();
    }


    public static function getRolePerms($role_id) { //obtengo todos los privilegios del rol
        $role = new Role();

        $stmt=new sQuery();
        $query="select sp.code
                from sec_role_privilege srp, sec_privileges sp
                where srp.id_privilege = sp.id_privilege
                and srp.id_role = :id_role";

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_role', $role_id);
        $stmt->dpExecute();
        $rows = $stmt->dpFetchAll();

        
        foreach($rows as $row) {
            $role->permissions[$row["code"]] = true;
        }
        return $role;
    }

    // check if a permission is set
    public function hasPerm($permission) {
        return isset($this->permissions[$permission]);
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
}




?>