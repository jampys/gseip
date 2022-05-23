<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-vehiculos').DataTable({
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            order: [[2, "asc"], [1, "asc"] ], //fecha_desde, fecha_hasta
            columnDefs: [
                {targets: 3, responsivePriority: 1, sortable: false },//botones
                {targets: 1, type: 'date-uk'}, //fecha_desde
                {targets: 2, type: 'date-uk'} //fecha_hasta
            ]

        });

        setTimeout(function () { //https://datatables.net/forums/discussion/41587/scrolly-misaligned-table-headers-with-bootstrap
            //$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
            t.columns.adjust();
        },200);


    });

</script>


<?php if(isset($view->vehiculos) && sizeof($view->vehiculos) > 0) {?>

    <br/>
    <div id="empleados-table">
            <table id="table-vehiculos" class="table table-condensed dpTable table-hover dt-responsive nowrap">
                <thead>
                <tr>
                    <th>Vehículo</th>
                    <th>F. desde</th>
                    <th>F. hasta</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($view->vehiculos as $ve): ?>
                    <tr data-id="<?php echo $ve['id_vehiculo_contrato']; ?>">
                        <td><span class="label label-primary" style="font-weight: normal"><?php echo $ve['matricula']; ?></span> <?php echo ($ve['nro_movil'])? '<span class="label label-default" style="font-weight: normal">Móvil: '.$ve['nro_movil'].'</span>' : '' ?></td>
                        <td><?php echo $ve['fecha_desde']; ?></td>
                        <td><?php echo $ve['fecha_hasta']; ?></td>

                        <td class="text-center">
                            <a class="view" href="javascript:void(0);" title="ver">
                                <i class="far fa-sticky-note dp_blue"></i>
                            </a>&nbsp;&nbsp;

                            <a class="<?php echo (PrivilegedUser::dhasPrivilege('GRV_ABM', array(1)))? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                                <i class="far fa-edit dp_blue"></i>
                            </a>&nbsp;&nbsp;

                            <a class="<?php echo ( PrivilegedUser::dhasPrivilege('GRV_ABM', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
    </div>




<?php }else{ ?>

    <br/>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle fa-fw"></i> El contrato no tiene vehículos asociados.
    </div>

<?php } ?>





