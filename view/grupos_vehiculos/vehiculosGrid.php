<script type="text/javascript">


    $(document).ready(function(){

        var t = $('#culino').DataTable({
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


    });

</script>


<?php if(isset($view->vehiculos) && sizeof($view->vehiculos) > 0) {?>

    <!--<div class="table-responsive fixedTable">

        <table class="table table-condensed dataTable table-hover">
            <thead>
            <tr>
                <th>Vehículo</th>
                <th>F. desde</th>
                <th>F. hasta</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->vehiculos as $et): ?>
                <tr data-id="<?php echo $et['id_grupo_vehiculo'];?>">
                    <td><?php echo $et['id_vehiculo'];?></td>
                    <td><?php echo $et['fecha_desde'];?></td>
                    <td><?php echo $et['fecha_hasta'];?></td>

                    <td class="text-center">
                        <a class="view" href="javascript:void(0);" data-id="<?php //echo $et['id_grupo_vehiculo'];?>" title="ver">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                        </a>
                    </td>

                    <td class="text-center">
                        <a class="<?php //echo ( PrivilegedUser::dhasAction('ETP_DELETE', array(1)) && $et['id_user'] == $_SESSION['id_user']  )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>


    </div>-->


    <div class="table-responsive" id="empleados-table">
            <table id="culino" class="table table-condensed dpTable table-hover">
                <thead>
                <tr>
                    <th>Vehículo</th>
                    <th>F. desde</th>
                    <th>F. hasta</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($view->vehiculos as $et): ?>
                    <tr data-id="<?php echo $et['id_grupo_vehiculo'];?>">
                        <td><?php echo $et['id_vehiculo'];?></td>
                        <td><?php echo $et['fecha_desde'];?></td>
                        <td><?php echo $et['fecha_hasta'];?></td>

                        <td class="text-center">
                            <a class="view" href="javascript:void(0);" data-id="<?php //echo $et['id_grupo_vehiculo'];?>" title="ver">
                                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                            </a>
                        </td>

                        <td class="text-center">
                            <a class="<?php //echo ( PrivilegedUser::dhasAction('ETP_DELETE', array(1)) && $et['id_user'] == $_SESSION['id_user']  )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
        <i class="fas fa-exclamation-triangle fa-fw"></i> El grupo no tiene vehículos registrados.
    </div>

<?php } ?>





