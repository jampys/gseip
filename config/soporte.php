<?php

class Soporte{



    public static function get_enum_values( $table, $field ){

        $stmt=new sQuery();
        $query = "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $p = $stmt->dpFetchAll();

        /*$type = $p[0]['Type'];
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $vals = explode("','", $matches[1]);
        return $vals;*/

        //$vals es un array asociativo que devuelve en el elemento enum la lista de items y en el elemento default el item por defecto
        $vals=array();

        $type = $p[0]['Type'];
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);

        $vals['enum'] = explode("','", $matches[1]);
        $vals['default'] = $p[0]['Default'];
        return $vals;

    }

    public static function getPeriodoActual(){
        //devuelve el periodo actual
        return date('Y');
    }

    public static function getPeriodos($start, $end){
        //devuelve todos los periodos, entre 2 periodos dados
        $periodos = array();

        for($p = $start; $p <= $end; $p++){
            $periodos[] = $p;
        }
        return $periodos;
    }


    public static function getProgressBarColor($percent){
        //devuelve la clase bootstrap que aplica color a la progress bar
        $rta = "";
        if($percent <= 30 ) $rta = "progress-bar-danger";
        elseif ($percent <= 80 ) $rta = "progress-bar-warning";
        else $rta = "progress-bar-success";

        return $rta;
    }







}

?>