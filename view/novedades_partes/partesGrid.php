﻿<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             },*/
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            "order": [[0, "desc"], [1, "asc"], [2, "asc"]], // 0=fecha_parte, 1=contrato, 2=cuadrilla
            columnDefs: [
                {targets: [ 0 ], type: 'date-uk', orderData: [ 0, 1 ]}, //fecha parte
                { responsivePriority: 1, targets: 8 },
                { responsivePriority: 2, targets: 6 }
            ]
        });



        //$(document).on("click", ".pdf", function(){
        $('.table-responsive').on("click", ".pdf", function(){
            alert('Funcionalidad en contrucción');
            /*params={};
            var attr = $('#search_empleado option:selected').attr('id_empleado'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
            params.id_empleado = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_empleado') : '';
            var attr = $('#search_empleado option:selected').attr('id_grupo');
            params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_grupo') : '';
            //params.id_vencimiento = $("#search_vencimiento").val();
            params.id_vencimiento = ($("#search_vencimiento").val()!= null)? $("#search_vencimiento").val() : '';
            params.id_contrato = $("#search_contrato").val();
            params.renovado = $('#search_renovado').prop('checked')? 1 : '';
            params.id_user = <?php //echo $_SESSION['id_user']; ?>
            //var nro_version = Number($('#version').val());
            //var lugar_trabajo = $('#lugar_trabajo').val();
            //var usuario  = "<?php //echo $_SESSION["USER_NOMBRE"].' '.$_SESSION["USER_APELLIDO"]; ?>";
            //var id_cia = "<?php //echo $_SESSION['ID_CIA']; ?>";
            //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
            var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
            //var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=sci_plan_version.rptdesign&p_periodo="+periodo+"&p_nro_version="+nro_version+"&p_lugar_trabajo="+lugar_trabajo+"&p_usuario="+usuario+"&p_id_cia="+id_cia;
            var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos_p.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_renovado="+params.renovado+"&p_id_cia="+params.id_empleado+"&p_id_user="+params.id_user;
            //var win = window.open(URL, "_blank", strWindowFeatures);
            var win = window.open(URL, "_blank");*/
            return false;
        });





    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>IN</th>
                <th>Contrato</th>
                <th>Cuadrilla / Empleado</th>
                <th>Área</th>
                <!--<th>Móvil</th>-->
                <th>Evento</th>
                <th></th>
                <th>Usuario</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->partes)) {
                foreach ($view->partes as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_parte']; ?>">
                        <td><?php echo $rp['fecha_parte']; ?></td>
                        <td><?php echo $rp['id_parte']; ?></td>
                        <td><?php echo $rp['contrato']; ?></td>
                        <td><?php echo $rp['cuadrilla']; ?></td>
                        <td><?php echo $rp['area']; ?></td>
                        <!--<td><?php //echo $rp['vehiculo']; ?></td>-->
                        <td><?php echo $rp['evento']; ?></td>
                        <td style="text-align: center">
                            <?php echo($rp['id_parte'])? '<i class="fas fa-car-side fa-fw dp_blue_nov" title="con novedad"></i>':'<i class="fas fa-car fa-fw dp_light_gray" title="sin novedad"></i>'; ?>&nbsp;
                            <?php echo($rp['concept_count']>0)? '<i class="fas fa-calculator fa-fw dp_blue_nov" title="novedad con conceptos"></i>':'<i class="fas fa-calculator fa-fw dp_light_gray" title="novedad sin conceptos"></i>'; ?>&nbsp;
                            <?php echo($rp['orden_count']>0)? '<i class="fas fa-clipboard-check fa-fw dp_blue_nov" title="novedad con órdenes"></i>':'<i class="fas fa-clipboard fa-fw dp_light_gray" title="novedad sin órdenes"></i>'; ?>
                        </td>
                        <td><?php $arr = explode("@", $rp['user'], 2);
                            echo $arr[0];?></td>


                        <td class="text-center">
                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="Ver novedad" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('PAR_UPDATE', array(1)) && !$rp['closed_date'] )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="Editar novedad" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo (
                                                    !$rp['closed_date'] &&
                                                    ((PrivilegedUser::dhasAction('PAR_DELETE', array(1)) && $rp['created_by'] == $_SESSION['id_user'])
                                                        ||
                                                    (PrivilegedUser::dhasPrivilege('USR_ABM', array(0))) //solo el administrador
                                                    )

                            )? 'delete':'disabled';

                                ?>" title="Eliminar novedad" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->


