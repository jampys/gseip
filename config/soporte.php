<?php

class Soporte{



    public static function get_enum_values( $table, $field ){

        $stmt=new sQuery();
        $query = "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $p = $stmt->dpFetchAll();

        $type = $p[0]['Type'];
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $vals = explode("','", $matches[1]);
        return $vals;

    }







}



?>