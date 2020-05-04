<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "fnInitComplete": function () {
                                $(this).show();
            },
            "stateSave": true
            //"order": [[6, "asc"], [7, "asc"], [5, "asc"] ], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
            columnDefs: [
                { responsivePriority: 1, targets: 6 }
            ]
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


        //$(document).on("click", ".pdf", function(){
        $('.table-responsive').on("click", ".pdf", function(){
            params={};
            var attr = $('#search_empleado option:selected').attr('id_empleado'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
            params.id_empleado = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_empleado') : '';
            var attr = $('#search_empleado option:selected').attr('id_grupo');
            params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_grupo') : '';
            //params.id_vencimiento = $("#search_vencimiento").val();
            params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
            params.id_contrato = $("#search_contrato").val();
            params.id_subcontratista = $("#search_subcontratista").val();
            params.renovado = $('#search_renovado').prop('checked')? 1 : '';
            params.id_user = "<?php echo $_SESSION['id_user']; ?>";
            //var nro_version = Number($('#version').val());
            //var lugar_trabajo = $('#lugar_trabajo').val();
            //var usuario  = "<?php //echo $_SESSION["USER_NOMBRE"].' '.$_SESSION["USER_APELLIDO"]; ?>";
            //var id_cia = "<?php //echo $_SESSION['ID_CIA']; ?>";
            //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
            var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
            //var URL="<?php //echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=sci_plan_version.rptdesign&p_periodo="+periodo+"&p_nro_version="+nro_version+"&p_lugar_trabajo="+lugar_trabajo+"&p_usuario="+usuario+"&p_id_cia="+id_cia;
            var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos_p.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_id_subcontratista="+params.id_subcontratista+"&p_renovado="+params.renovado+"&p_id_user="+params.id_user;
            //var win = window.open(URL, "_blank", strWindowFeatures);
            var win = window.open(URL, "_blank");
            return false;
        });





    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-10">

    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Móvil</th>
                <th>Vehículo</th>
                <th>Vencimiento</th>
                <th style="text-align: center">Requerido</th>
                <th>Fecha. Vto.</th>
                <th>Desactivado</th>
                <th>Estado</th>

            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->renovaciones_vehiculos)) {
                foreach ($view->renovaciones_vehiculos as $rv):   ?>
                    <tr data-id="<?php echo $rv['id_renovacion']; ?>"
                        id_vehiculo="<?php echo $rv['id_vehiculo']; ?>"
                        id_vencimiento="<?php echo $rv['id_vencimiento']; ?>"

                        style="background-color: <?php echo $rv['color']; ?>" >
                        <td><?php echo $rv['nro_movil']; ?></td>
                        <td><?php echo $rv['matricula'].' '.$rv['modelo']; ?></td>
                        <td><?php echo $rv['vencimiento']; ?></td>
                        <td style="text-align: center">


                        <a class="new <?php echo (PrivilegedUser::dhasPrivilege('EMP_ABM', array(1)) )? '': 'disabled' ?>" href="" title="modificar vencimientos">
                            <span class="requerido">
                                <?php echo ($rv['id_vehiculo_vencimiento'])? 'SI' : 'NO' ; ?>
                            </span>
                        </a>


                        </td>


                        <td><?php echo $rv['fecha_vencimiento']; ?></td>
                        <td><?php echo $rv['disabled']; ?></td>
                        <td>
                            <?php if($rv['id_vehiculo_vencimiento'] && (!$rv['id_renovacion'] || $rv['disabled'] || $rv['isVencida']<= 0  ) ){ ?>
                                <i class="fas fa-exclamation-triangle dp_red"></i>
                            <?php } else{ ?>
                                <i class="fas fa-check dp_green"></i>
                            <?php } ?>

                            &nbsp;

                            <?php if($rv['id_vehiculo_vencimiento'] && !$rv['id_renovacion']){ $view->details['sin_datos']++; ?>
                                <a class="new <?php echo (PrivilegedUser::dhasPrivilege('RPE_ABM', array(1)) )? '': 'disabled' ?>" href="" title="Cargar vencimiento">
                                    <span class="dp_red">Sin datos</span>
                                </a>

                            <?php } elseif($rv['id_vehiculo_vencimiento'] && $rv['disabled']){ $view->details['desactivados']++; ?>
                                <a class="edit <?php echo (PrivilegedUser::dhasPrivilege('RPE_ABM', array(1)) )? '': 'disabled' ?>" href="" title="Habilitar vencimiento">
                                    <span class="dp_red">Desactivado</span>
                                </a>

                            <?php } elseif($rv['id_vehiculo_vencimiento'] && $rv['isVencida']<= 0){ $view->details['vencidos']++;  ?>
                                <a class="renovar <?php echo (PrivilegedUser::dhasPrivilege('RPE_ABM', array(1)) )? '': 'disabled' ?>" href="" title="Renovar vencimiento">
                                    <span class="dp_red">Vencido</span>
                                </a>

                            <?php } elseif($rv['id_vehiculo_vencimiento'] && $rv['isVencida']> 0){ $view->details['actualizados']++;  ?>
                                <!--<span class="dp_green">Vigente</span>-->
                                <a class="edit <?php echo (PrivilegedUser::dhasPrivilege('RPE_ABM', array(1)) )? '': 'disabled' ?>" href="" title="Habilitar vencimiento">
                                    <span class="dp_green">Vigente</span>
                                </a>

                            <?php } elseif(!$rv['id_empleado_vencimiento']){ $view->details['no_aplica']++;  ?>
                                <span class="dp_green">No aplica</span>
                            <?php }?>

                        </td>



                        <!--<td class="text-center">
                            <?php if($rv['cant_uploads']> 0 ){ ?>
                                <a href="#" title="<?php echo $rv['cant_uploads']; ?> adjuntos" >
                                    <span class="glyphicon glyphicon-paperclip dp_gray" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php } ?>&nbsp;&nbsp;

                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <?php if($rv['id_rnv_renovacion']){ ?>
                                <a href="javascript:void(0);" data-toggle="tooltip" title="Nro. renov: <?php echo $rv['id_rnv_renovacion']; ?>" >
                                    <span class="glyphicon glyphicon-ok-sign dp_blue" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) )? 'renovar' : 'disabled' ?>" href="javascript:void(0);" title="renovar">
                                    <i class="far fa-clone dp_blue"></i>
                                </a>
                            <?php } ?>&nbsp;&nbsp;


                            <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) && !$rv['id_rnv_renovacion']  )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;


                            <a class="<?php //echo ( PrivilegedUser::dhasAction('RPE_DELETE', array(1)) && !$rp['id_rnv_renovacion'] )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>
                        </td>-->
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>


        <!--<br/>
        <div class="pull-right pdf">
            <a href="index.php?action="><i class="far fa-file-pdf fa-fw fa-2x dp_blue"></i></a>
        </div>-->



    <!--</div>-->

</div>

<div class="col-md-2">

    <div class="panel panel-default">
        <div class="panel-body" style="background-color: #e5e5e5">


            <b>Total registros:</b>&nbsp;<span><?php echo sizeof($view->renovaciones_personal); ?></span><br/>
            <b>Vigentes:</b>&nbsp;<span class="dp_green"><?php echo ($view->details['actualizados'])? $view->details['actualizados'] : 0; ?></span><br/>
            <b>No aplica:</b>&nbsp;<span class="dp_green"><?php echo ($view->details['no_aplica'])? $view->details['no_aplica'] : 0; ?></span><br/>
            <b>Vencidos:</b>&nbsp;<span class="dp_red"><?php echo ($view->details['vencidos'])? $view->details['vencidos'] : 0; ?></span><br/>
            <b>Desactivados:</b>&nbsp;<span class="dp_red"><?php echo ($view->details['desactivados'])? $view->details['desactivados'] : 0; ?></span><br/>
            <b>Sin datos:</b>&nbsp;<span class="dp_red"><?php echo ($view->details['sin_datos'])? $view->details['sin_datos'] : 0; ?></span><br/>

        </div>
    </div>



</div>



<!--<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar la renovación?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>-->








