<?php
class ContratoEmpleadoProceso
{
    private $id_empleado_contrato_proceso;
    private $id_empleado_contrato;
    private $id_proceso;

    //GETTERS
    function getIdEmpleadoContratoProceso()
    { return $this->id_empleado_contrato_proceso;}

    function getIdEmpleadoContrato()
    { return $this->id_empleado_contrato;}

    function getIdProceso()
    { return $this->id_proceso;}


    //SETTERS
    function setIdEmpleadoContratoProceso($val)
    { $this->id_empleado_contrato_proceso=$val;}

    function setIdEmpleadoContrato($val)
    { $this->id_empleado_contrato=$val;}

    function setIdProceso($val)
    {  $this->id_proceso=$val;}



    function __construct($nro=0){ //constructor //ok
        if ($nro!=0){

            $stmt=new sQuery();
            $query="select *
                    from empleado_contrato_proceso where id_empleado_contrato_proceso = :nro";
            $stmt->dpPrepare($query);
            $stmt->dpBind(':nro', $nro);
            $stmt->dpExecute();
            $rows = $stmt ->dpFetchAll();

            $this->setIdEmpleadoContratoProceso($rows[0]['id_empleado_contrato_proceso']);
            $this->setIdEmpleadoContrato($rows[0]['id_empleado_contrato']);
            $this->setIdProceso($rows[0]['id_proceso']);
        }
    }

    //Devuelve todos los procesos de un determinado empleado-contrato
    public static function getContratoEmpleadoProceso($id_contrato_empleado) { //ok
        $stmt=new sQuery();
        $query = "select *
                  from empleado_contrato_proceso ecp
                  where ecp.id_empleado_contrato = :id_contrato_empleado";
        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_contrato_empleado', $id_contrato_empleado);
        $stmt->dpExecute();
        return $stmt->dpFetchAll();
    }


    public function contratoEmpleadoProceso(){ //ok

        $stmt=new sQuery();
        $query = 'CALL sp_contratoEmpleadoProceso(
                                                    :id_empleado_contrato,
                                                    :id_proceso,
                                                    @flag
                                                  )';

        $stmt->dpPrepare($query);
        $stmt->dpBind(':id_empleado_contrato', $this->getIdEmpleadoContrato());
        $stmt->dpBind(':id_proceso', $this->getIdProceso());
        $stmt->dpExecute();

        $stmt->dpCloseCursor();
        $query = "select @flag as flag";
        $stmt->dpPrepare($query);
        $stmt->dpExecute();
        $flag = $stmt->dpFetchAll();
        return ($flag)? intval($flag[0]['flag']) : 0;


    }

}

?>