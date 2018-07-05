<?php if(isset($view->empleados) && sizeof($view->empleados) > 0) {?>

    <table class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Empleado</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($view->empleados as $em): ?>
            <tr data-id="<?php echo $em['id_cuadrilla_empleado'];?>">
                <td><?php echo $em['fecha'];?></td>
                <td><?php echo $em['apellido']." ".$em['nombre'];?></td>

                <td class="text-center">
                    <a class="view" href="javascript:void(0);" data-id="<?php echo $em['id_cuadrilla_empleado'];?>" title="ver">
                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                    </a>
                </td>

                <td class="text-center">
                    <a class="<?php echo ( PrivilegedUser::dhasAction('ETP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>
                </td>

                <td class="text-center">
                    <a class="<?php echo ( PrivilegedUser::dhasAction('ETP_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


<?php }else{ ?>

    <br/>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle fa-fw"></i> La cuadrilla aún no tiene empleados registrados.
    </div>

<?php } ?>





