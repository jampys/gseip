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

        /*while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $role->permissions[$row["perm_desc"]] = true;
        }
        return $role;*/

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

    public function __construct() {
        parent::__construct();
    }

    // override User method
    public static function getByUsername($username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $sth = $GLOBALS["DB"]->prepare($sql);
        $sth->execute(array(":username" => $username));
        $result = $sth->fetchAll();

        if (!empty($result)) {
            $privUser = new PrivilegedUser();
            $privUser->user_id = $result[0]["user_id"];
            $privUser->username = $username;
            $privUser->password = $result[0]["password"];
            $privUser->email_addr = $result[0]["email_addr"];
            $privUser->initRoles();
            return $privUser;
        } else {
            return false;
        }
    }

    // populate roles with their associated permissions
    protected function initRoles() {
        $this->roles = array();
        $sql = "SELECT t1.role_id, t2.role_name FROM user_role as t1
                JOIN roles as t2 ON t1.role_id = t2.role_id
                WHERE t1.user_id = :user_id";
        $sth = $GLOBALS["DB"]->prepare($sql);
        $sth->execute(array(":user_id" => $this->user_id));

        while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
            $this->roles[$row["role_name"]] = Role::getRolePerms($row["role_id"]);
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