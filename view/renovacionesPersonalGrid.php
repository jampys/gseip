<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "fnInitComplete": function () {
                                $(this).show();
            },
            "stateSave": true,
            "order": [[6, "asc"], [7, "asc"], [5, "asc"] ], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 6 ]}, //fecha
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4, 6 ]}, //fecha_emision
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 6 ]}, //fecha_vencimiento
                {targets: [ 6 ], orderData: [ 6], visible: false}, //priority
                {targets: [ 7 ], orderData: [ 7], visible: false}, //renovacion
                { responsivePriority: 1, targets: 8 }
            ]
        });



        $(".pdf").on("click", "a", function(){
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
            var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=html&__report=gseip_vencimientos_p.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_id_subcontratista="+params.id_subcontratista+"&p_renovado="+params.renovado+"&p_id_user="+params.id_user;
            //var win = window.open(URL, "_blank", strWindowFeatures);
            var win = window.open(URL, "_blank");
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
                <th>Nro. rnv</th>
                <th>Fecha</th>
                <th>vencimiento</th>
                <th>empleado / grupo</th>
                <th>F. emisión</th>
                <th>F. vto.</th>
                <th style="display: none">Priority</th>
                <th style="display: none">Rnv</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->renovaciones_personal)) {
                foreach ($view->renovaciones_personal as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_renovacion']; ?>">
                        <td><?php echo $rp['id_renovacion']; ?></td>
                        <td><?php echo $rp['created_date']; ?></td>
                        <td><?php echo $rp['vencimiento']; ?></td>
                        <td><?php echo ($rp['id_empleado'])? $rp['empleado'] : $rp['grupo']; ?></td>
                        <td><?php echo $rp['fecha_emision']; ?></td>
                        <td style="background-color: <?php echo $rp['color']; ?>"><?php echo $rp['fecha_vencimiento']; ?></td>
                        <td style="display: none"><?php echo $rp['priority']; ?></td>
                        <td style="display: none"><?php echo $rp['id_rnv_renovacion']; ?></td>

                        <td class="text-center">
                            <?php if($rp['cant_uploads']> 0 ){ ?>
                                <a href="#" title="<?php echo $rp['cant_uploads']; ?> adjuntos" >
                                    <span class="glyphicon glyphicon-paperclip dp_gray" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            <?php } ?>&nbsp;&nbsp;

                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <?php if($rp['id_rnv_renovacion']){ ?>
                                <a href="javascript:void(0);" data-toggle="tooltip" title="Nro. renov: <?php echo $rp['id_rnv_renovacion']; ?>" >
                                    <span class="glyphicon glyphicon-ok-sign dp_blue" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) )? 'renovar' : 'disabled' ?>" href="javascript:void(0);" title="renovar">
                                    <i class="far fa-clone dp_blue"></i>
                                </a>
                            <?php } ?>&nbsp;&nbsp;

                            <!-- si tiene permiso y no fue renovado -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) && !$rp['id_rnv_renovacion']  )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso y no fue renovado -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_DELETE', array(1)) && !$rp['id_rnv_renovacion'] )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>


        <br/>
        <div class="pull-right pdf">
            <a href="index.php?action="><i class="far fa-file-pdf fa-fw fa-2x dp_blue"></i></a>
        </div>

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->










