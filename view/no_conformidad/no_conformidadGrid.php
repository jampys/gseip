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



    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">





    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Tipo acción</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->renovaciones_personal)) {
                foreach ($view->renovaciones_personal as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_no_conformidad']; ?>">
                        <td><?php echo $rp['created_date']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['tipo']; ?></td>
                        <td><?php echo $rp['tipo_accion']; ?></td>

                        <td class="text-center">

                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso y no fue renovado -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) && !$rp['id_rnv_renovacion']  )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <?php if($rp['id_rnv_renovacion']){ ?>
                                <a href="javascript:void(0);" data-toggle="tooltip" title="Nro. renov: <?php echo $rp['id_rnv_renovacion']; ?>" >
                                    <span class="glyphicon glyphicon-ok-sign dp_blue" aria-hidden="true"></span>
                                </a>
                            <?php } else{ ?>
                                <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_UPDATE', array(1)) )? 'renovar' : 'disabled' ?>" href="javascript:void(0);" title="renovar">
                                    <i class="fas fa-share dp_blue"></i>
                                </a>
                            <?php } ?>&nbsp;&nbsp;

                            <!-- si tiene permiso y no fue renovado -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('RPE_DELETE', array(1)) && !$rp['id_rnv_renovacion'] )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
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










