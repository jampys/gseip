<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-vehiculos').DataTable({
            responsive: true,
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true,
            //order: [[1, "asc"], [2, "asc"]], // 3=fecha_hasta, 1=certif
            columnDefs: [
                { responsivePriority: 1, targets: 2 }
            ]
        });

        setTimeout(function () { //https://datatables.net/forums/discussion/41587/scrolly-misaligned-table-headers-with-bootstrap
            //$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
            t.columns.adjust();
        },200);


    });

</script>


<?php if(isset($view->acciones) && sizeof($view->acciones) > 0) {?>
    
    <div id="empleados-table">
            <table id="table-vehiculos" class="table table-condensed dpTable table-hover dt-responsive nowrap">
                <thead>
                <tr>
                    <th>Acción</th>
                    <th>Usr.</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($view->acciones as $ve): ?>
                    <tr data-id="<?php echo $ve['id_accion']; ?>">
                        <td><?php echo $ve['accion']; ?></td>
                        <td><?php $arr = explode("@", $ve['user'], 2);
                            echo $arr[0];?></td>

                        <td class="text-center">
                            <a class="view" href="javascript:void(0);" title="ver">
                                <span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <a class="<?php echo (PrivilegedUser::dhasPrivilege('GRV_ABM', array(1)))? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                                <span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span>
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
        <i class="fas fa-exclamation-triangle fa-fw"></i> La No conformidad no tiene acciones registradas.
    </div>

<?php } ?>





