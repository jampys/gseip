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
            //"order": [[6, "asc"], [7, "asc"], [5, "asc"] ], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
            columnDefs: [
                {targets: [ 0 ], type: 'date-uk', orderData: [ 0, 1 ]} //fecha
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
                <th>Nro. NC</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Tipo acción</th>
                <th>Resp. Seguimiento</th>
                <th>Estado</th>
                <th></th>

            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->renovaciones_personal)) {
                foreach ($view->renovaciones_personal as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_no_conformidad']; ?>">
                        <td><?php echo $rp['id_no_conformidad']; ?></td>
                        <td><?php echo $rp['created_date']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['tipo']; ?></td>
                        <td><?php echo $rp['tipo_accion']; ?></td>
                        <td><?php echo $rp['responsable_seguimiento']; ?></td>
                        <td><?php //echo $rp['responsable_seguimiento']; ?></td>

                        <td class="text-center">
                            <a class="acciones" href="javascript:void(0);" data-id="<?php echo $rp['id_no_conformidad'] ?>" title="Acciones"><i class="fas fa-th-list dp_blue"></i></a>&nbsp;&nbsp;
                            <a class="verificaciones" href="javascript:void(0);" data-id="<?php echo $rp['id_no_conformidad'] ?>" title="Verificaciones"><i class="fas fa-th-list dp_blue"></i></a>&nbsp;&nbsp;

                            <a class="view" title="Ver" href="javascript:void(0);">
                                <i class="far fa-eye dp_blue"></i>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" title="Editar" href="javascript:void(0);">
                                <i class="far fa-edit dp_blue"></i>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="Borrar" href="javascript:void(0);">
                                <i class="far fa-trash-alt dp_red"></i>
                            </a>&nbsp;&nbsp;

                            <a class="pdf" href="javascript:void(0);" data-id="<?php echo $rp['id_no_conformidad'] ?>" title="Emitir certificado"><i class="far fa-file-pdf dp_blue"></i></a>
                        </td>

                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>


    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->










