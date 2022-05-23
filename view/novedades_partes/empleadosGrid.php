<?php if(isset($view->empleados) && sizeof($view->empleados) > 0) {?>

    <table class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <!--<th>Empleado</th>
            <th>Conductor</th>
            <th></th>
            <th></th>
            <th></th>-->
        </tr>
        </thead>
        <tbody>
        <?php foreach ($view->empleados as $em): ?>
            <tr data-id="<?php echo $em['id_parte_empleado'];?>">
                <td><b><?php echo $em['legajo']; ?></b>&nbsp;<?php echo $em['apellido'].' '.$em['nombre'];?></td>
                <td><?php echo $em['convenio'];?></td>
                <td><?php echo ($em['conductor'])? '<i class="far fa-check-circle fa-fw" style="color: #49ed0e" title="conductor"></i>':''; ?></td>
                <!--<td style="text-align: center"><?php //echo($et['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>
                <td><?php //echo $et['user'];?></td>-->

                <td class="text-center">
                    <a class="view" href="javascript:void(0);" data-id="<?php //echo $et['id_etapa'];?>" title="ver">
                        <i class="far fa-sticky-note dp_blue"></i>
                    </a>&nbsp;&nbsp;

                    <a class="<?php //echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' /*&& $et['id_user'] == $_SESSION['id_user']*/  )? 'edit' : 'disabled' ?>disabled" href="javascript:void(0);" title="editar">
                        <i class="far fa-edit dp_blue"></i>
                    </a>&nbsp;&nbsp;

                    <a class="<?php //echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' /*&& $et['id_user'] == $_SESSION['id_user'] */ )? 'delete' : 'disabled' ?>disabled" title="borrar" href="javascript:void(0);">
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
        <i class="fas fa-exclamation-triangle fa-fw"></i> El parte no tiene empleados registrados.
    </div>

<?php } ?>





