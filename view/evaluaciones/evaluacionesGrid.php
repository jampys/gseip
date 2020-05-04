<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            responsive: true,
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true,
            columnDefs: [
                { responsivePriority: 1, targets: 4 }
            ]
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });




    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Contrato</th>
                <th>Puesto</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($view->evaluaciones)) {
             foreach ($view->evaluaciones as $evaluacion):   ?>
                <tr id_empleado="<?php echo $evaluacion['id_empleado'];?>"
                    id_plan_evaluacion="<?php echo $evaluacion['id_plan_evaluacion'];?>"
                    periodo="<?php echo $evaluacion['periodo'];?>"
                    cerrado="<?php echo $evaluacion['cerrado'];?>"
                    >
                    <td><?php echo $evaluacion['apellido'];?></td>
                    <td><?php echo $evaluacion['nombre'];?></td>
                    <td><?php echo $evaluacion['contrato'];?></td>
                    <td><?php echo $evaluacion['puesto'];?></td>

                    <!-- evaluacion de aspectos generales -->
                    <td class="text-center">
                        <a class="<?php echo (!$evaluacion['cerrado'] &&
                            PrivilegedUser::dhasPrivilege('EAD_AGS', array(1))
                        )? 'loadEaag' : 'disabled' ?>" href="javascript:void(0);" title="Evaluación aspectos generales" >
                            <span class="<?php echo ($evaluacion['hasAllEaag'])? 'glyphicon glyphicon-check text-success': 'glyphicon glyphicon-unchecked dp_blue';  ?>" aria-hidden="true"></span>
                        </a>&nbsp;&nbsp;

                    <!-- evaluacion de competencias -->
                        <a class="<?php echo (  !$evaluacion['cerrado'] &&
                                                ((PrivilegedUser::dhasPrivilege('EAD_COM', array(51)) && $evaluacion['isInSup']) ||
                                                    (PrivilegedUser::dhasPrivilege('EAD_COM', array(52)) && $evaluacion['isSup']) ||
                                                PrivilegedUser::dhasPrivilege('EAD_COM', array(0)))
                            )? 'loadEac' : 'disabled' ?>" href="javascript:void(0);" title="Evaluación competencias" >
                            <span class="<?php echo ($evaluacion['hasAllEac'])? 'glyphicon glyphicon-check text-success': 'glyphicon glyphicon-unchecked dp_blue';  ?>" aria-hidden="true"></span>
                        </a>&nbsp;&nbsp;


                    <!-- evaluacion de objetivos -->
                        <a class="<?php echo (  !$evaluacion['cerrado'] &&
                                                ((PrivilegedUser::dhasPrivilege('EAD_OBJ', array(51)) && $evaluacion['isInSup']) ||
                                                    (PrivilegedUser::dhasPrivilege('EAD_COM', array(52)) && $evaluacion['isSup']) ||
                                                PrivilegedUser::dhasPrivilege('EAD_OBJ', array(0)))
                            )? 'loadEao' : 'disabled' ?>" href="javascript:void(0);" title="Evaluación objetivos" >
                            <span class="<?php echo ($evaluacion['hasAllEao'])? 'glyphicon glyphicon-check text-success': 'glyphicon glyphicon-unchecked dp_blue';  ?>" aria-hidden="true"></span>
                        </a>&nbsp;&nbsp;


                    <!-- evaluacion conclusiones -->
                        <a class="<?php echo (  !$evaluacion['cerrado'] &&
                                                ((PrivilegedUser::dhasPrivilege('EAD_COM', array(51)) && $evaluacion['isInSup']) ||
                                                    (PrivilegedUser::dhasPrivilege('EAD_COM', array(52)) && $evaluacion['isSup']) ||
                                                PrivilegedUser::dhasPrivilege('EAD_COM', array(0)))
                        )? 'loadEaconcl' : 'disabled' ?>" href="javascript:void(0);" title="Conclusiones" >
                            <span class="<?php echo ($evaluacion['hasEaconcl'])? 'glyphicon glyphicon-check text-success': 'glyphicon glyphicon-unchecked dp_blue';  ?>" aria-hidden="true"></span>
                        </a>&nbsp;&nbsp;

                    <!-- reporte individual -->
                        <a class="<?php echo (  //!$evaluacion['cerrado'] &&
                                                ((PrivilegedUser::dhasPrivilege('EAD_COM', array(51)) && $evaluacion['isInSup']) ||
                                                    (PrivilegedUser::dhasPrivilege('EAD_COM', array(52)) && $evaluacion['isSup']) ||
                                                PrivilegedUser::dhasPrivilege('EAD_COM', array(0)))
                            )? 'reporte' : 'disabled' ?>" href="javascript:void(0);" title="Reporte de evaluación" >
                            <i class="far fa-file-pdf fa-fw dp_blue"></i>
                        </a>
                    </td>


                </tr>
            <?php endforeach; } ?>
            </tbody>
        </table>

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el objetivo?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








