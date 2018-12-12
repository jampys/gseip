<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });



        $('.table-responsive').on("click", ".gauss", function(e){
            //e.preventDefault();
            //alert('Funcionalidad en desarrollo');
            //throw new Error();



            params={};
            params.action = "evaluaciones";
            params.operation="loadGauss";
            params.periodo = $("#periodo").val();
            params.search_contrato = $("#search_contrato").val();
            //params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
            //params.renovado = $('#search_renovado').prop('checked')? 1 : '';
            //alert(params.search_contrato);


            $('#popupbox').load('index.php', params,function(){
                $('#myModal').modal();
            });


            //var nro_version = Number($('#version').val());

            //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
            //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
            //var URL="<?php //echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos_v.rptdesign&p_id_vehiculo="+params.id_vehiculo+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_id_subcontratista="+params.id_subcontratista+"&p_renovado="+params.renovado+"&p_id_user="+params.id_user;
            //var win = window.open(URL, "_blank", strWindowFeatures);
            //var win = window.open(URL, "_blank");
            return false;
        });




    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">


    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>Contrato</th>
                <th>Puesto</th>
                <th></th>
                <th></th>
                <th></th>
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

                    <!-- evaluacion de competencias -->
                    <td class="text-center">
                        <a class="<?php echo (  !$evaluacion['cerrado'] &&
                                                ((PrivilegedUser::dhasPrivilege('EAD_COM', array(51)) && $evaluacion['isInSup']) ||
                                                PrivilegedUser::dhasPrivilege('EAD_COM', array(0)))
                            )? 'loadEac' : 'disabled' ?>" href="javascript:void(0);" title="Evaluación competencias" >
                            <span class="<?php echo ($evaluacion['hasAllEac'])? 'glyphicon glyphicon-check text-success': 'glyphicon glyphicon-unchecked';  ?>" aria-hidden="true"></span>
                        </a>
                    </td>

                    <!-- evaluacion de aspectos generales -->
                    <td class="text-center">
                        <a class="<?php echo (!$evaluacion['cerrado'] &&
                                              PrivilegedUser::dhasPrivilege('EAD_AGS', array(1))
                            )? 'loadEaag' : 'disabled' ?>" href="javascript:void(0);" title="Evaluación aspectos generales" >
                            <span class="<?php echo ($evaluacion['hasAllEaag'])? 'glyphicon glyphicon-check text-success': 'glyphicon glyphicon-unchecked';  ?>" aria-hidden="true"></span>
                        </a>
                    </td>


                    <!-- evaluacion de objetivos -->
                    <td class="text-center">
                        <a class="<?php echo (  !$evaluacion['cerrado'] &&
                                                ((PrivilegedUser::dhasPrivilege('EAD_OBJ', array(51)) && $evaluacion['isInSup']) ||
                                                PrivilegedUser::dhasPrivilege('EAD_OBJ', array(0)))
                            )? 'loadEao' : 'disabled' ?>" href="javascript:void(0);" title="Evaluación objetivos" >
                            <span class="<?php echo ($evaluacion['hasAllEao'])? 'glyphicon glyphicon-check text-success': 'glyphicon glyphicon-unchecked';  ?>" aria-hidden="true"></span>
                        </a>
                    </td>

                    <!-- reporte individual -->
                    <td class="text-center">
                        <a class="<?php echo (  !$evaluacion['cerrado'] &&
                                                ((PrivilegedUser::dhasPrivilege('EAD_COM', array(51)) && $evaluacion['isInSup']) ||
                                                PrivilegedUser::dhasPrivilege('EAD_COM', array(0)))
                            )? 'reporte' : 'disabled' ?>" href="javascript:void(0);" title="Reporte de evaluación" >
                            <i class="far fa-file-pdf fa-fw"></i>
                        </a>
                    </td>


                </tr>
            <?php endforeach; } ?>
            </tbody>
        </table>

        <br/>
        <div class="pull-right gauss">
            <a href="#" title="Función de densidad">
                <i class="fas fa-chart-area fa-fw fa-2x"></i>
            </a>
        </div>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el objetivo?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








