<?php if(isset($view->empleados) && sizeof($view->empleados) > 0) {?>

    <table class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <th>Empleado</th>
            <th>Ctor.</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($view->empleados as $em): ?>
            <tr data-id="<?php echo $em['id_cuadrilla_empleado'];?>">
                <td><b><?php echo $em['legajo'];?>&nbsp;</b><?php echo $em['apellido']." ".$em['nombre'];?></td>
                <td><?php echo ($em['conductor'])? '<i class="far fa-check-circle fa-fw" style="color: #49ed0e" title="conductor"></i>':''; ?></td>

                <td class="text-center">
                    <a class="view" href="javascript:void(0);" data-id="<?php echo $em['id_cuadrilla_empleado'];?>" title="ver">
                        <span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span>
                    </a>&nbsp;&nbsp;

                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('CUA_ABM', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                        <span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span>
                    </a>&nbsp;&nbsp;

                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('CUA_ABM', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                        <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
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





