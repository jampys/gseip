<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#table-postulantes').DataTable({
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            //deferRender:    true,
            scrollY:        150,
            scrollCollapse: true,
            scroller:       true
            /*"columnDefs": [
                {"width": "30%", "targets": 0}, //empleado
                {"width": "55%", "targets": 1}, //puesto
                {"width": "5%", "targets": 2}, //ver
                {"width": "5%", "targets": 3}, //editar
                {"width": "5%", "targets": 4} //eliminar
            ]*/

        });

        setTimeout(function () { //https://datatables.net/forums/discussion/41587/scrolly-misaligned-table-headers-with-bootstrap
            //$($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
            t.columns.adjust();
        },200);


    });

</script>


<?php if(isset($view->postulaciones) && sizeof($view->postulaciones) > 0) {?>

    <br/>
    <div class="table-responsive">
            <table id="table-postulantes" class="table table-condensed dpTable table-hover">
                <thead>
                <tr>
                    <th>Postulante</th>
                    <th>Etapa</th>
                    <th>Aplica</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($view->postulaciones as $pos): ?>
                    <tr data-id="<?php echo $pos['id_postulacion'];?>">
                        <td><?php echo $pos['postulante']; ?></td>
                        <td><?php echo $pos['etapa']; ?></td>
                        <td><?php echo($pos['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>

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
        <i class="fas fa-exclamation-triangle fa-fw"></i> No existen candidatos para la búsqueda seleccionada. Para afectar un postulante a la búsqueda diríjase a
        <?php if (true /*PrivilegedUser::dhasPrivilege('CON_VER', array(1))*/ ) { ?>
            <a href="index.php?action=postulaciones">Postulaciones</a></p>
        <?php } ?>
    </div>

<?php } ?>





