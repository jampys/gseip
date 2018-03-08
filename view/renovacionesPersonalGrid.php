<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/

            "fnInitComplete": function () {
                                $(this).show(); },


            "stateSave": true,
            "order": [[6, "asc"], [7, "asc"], [5, "asc"] ], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
            /*"columnDefs": [
                { type: 'date-uk', targets: 1 }, //fecha
                { type: 'date-uk', targets: 4 }, //fecha_emision
                { type: 'date-uk', targets: 5 } //fecha_vencimiento
            ]*/
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 6 ]}, //fecha
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4, 6 ]}, //fecha_emision
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 6 ]}, //fecha_vencimiento
                {targets: [ 6 ], orderData: [ 6]}, //priority
                {targets: [ 7 ], orderData: [ 7]} //renovacion
            ]
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


        $(document).on("click", ".pdf", function(){
            params={};
            var attr = $('#search_empleado option:selected').attr('id_empleado'); // For some browsers, `attr` is undefined; for others,`attr` is false.  Check for both.
            params.id_empleado = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_empleado') : '';
            var attr = $('#search_empleado option:selected').attr('id_grupo');
            params.id_grupo = (typeof attr !== typeof undefined && attr !== false)? $('#search_empleado option:selected').attr('id_grupo') : '';
            params.id_vencimiento = $("#search_vencimiento").val();
            params.id_contrato = $("#search_contrato").val();
            params.renovado = $('#search_renovado').prop('checked')? 1 : '';
            params.id_user = <?php echo $_SESSION['id_user']; ?>
            //var nro_version = Number($('#version').val());
            //var lugar_trabajo = $('#lugar_trabajo').val();
            //var usuario  = "<?php echo $_SESSION["USER_NOMBRE"].' '.$_SESSION["USER_APELLIDO"]; ?>";
            //var id_cia = "<?php echo $_SESSION['ID_CIA']; ?>";
            //var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes, top=200,left=400";
            var strWindowFeatures = "location=yes,height=500,width=800,scrollbars=yes,status=yes";
            //var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=sci_plan_version.rptdesign&p_periodo="+periodo+"&p_nro_version="+nro_version+"&p_lugar_trabajo="+lugar_trabajo+"&p_usuario="+usuario+"&p_id_cia="+id_cia;
            var URL="<?php echo $GLOBALS['ini']['report_url']; ?>frameset?__format=pdf&__report=gseip_vencimientos.rptdesign&p_id_empleado="+params.id_empleado+"&p_id_grupo="+params.id_grupo+"&p_id_vencimiento="+params.id_vencimiento+"&p_id_contrato="+params.id_contrato+"&p_renovado="+params.renovado+"&p_id_cia="+params.id_empleado+"&p_id_user="+params.id_user;
            //var win = window.open(URL, "_blank", strWindowFeatures);
            var win = window.open(URL, "_blank");
            return false;
        });





    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">





    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nro. rnv</th>
                <th>Fecha</th>
                <th>vencimiento</th>
                <th>empleado / grupo</th>
                <th>F. emisión</th>
                <th>F. vto.</th>
                <th style="display: none">Priority</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->renovaciones_personal)) {
                foreach ($view->renovaciones_personal as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_renovacion']; ?>" style="background-color: <?php echo $rp['color']; ?>" >
                        <td><?php echo $rp['id_renovacion']; ?></td>
                        <td><?php echo $rp['fecha']; ?></td>
                        <td><?php echo $rp['vencimiento']; ?></td>
                        <td><?php echo ($rp['id_empleado'])? $rp['empleado'] : $rp['grupo']; ?></td>
                        <td><?php echo $rp['fecha_emision']; ?></td>
                        <td><?php echo $rp['fecha_vencimiento']; ?></td>
                        <td style="display: none"><?php echo $rp['priority']; ?></td>

                        <td class="text-center">
                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open" title="ver" aria-hidden="true"></span>
                            </a>
                        </td>

                        <td class="text-center">
                            <?php if($rp['id_rnv_renovacion']){ ?>
                                <a href="javascript:void(0);" data-toggle="tooltip" title="Nro. renov: <?php echo $rp['id_rnv_renovacion']; ?>" >
                                    <span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) )? 'renovar' : 'disabled' ?>" href="javascript:void(0);" title="renovar">
                                    <i class="far fa-clone"></i>
                                </a>

                            <?php } ?>

                        </td>

                        <td class="text-center">
                            <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit" title="editar" aria-hidden="true"></span>
                            </a>
                        </td>
                        <td class="text-center">
                            <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>


        <br/>
        <div class="pull-right pdf">
            <a href="index.php?action="><i class="far fa-file-pdf fa-fw fa-2x"></i></a>
        </div>

    </div>

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar la renovación?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








