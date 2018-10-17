<?php if(isset($view->tareas) && sizeof($view->tareas) > 0) {?>

    <table class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <!--<th>Tarea</th>
            <th>F. Inicio</th>
            <th>F. Fin</th>
            <th></th>
            <th></th>
            <th></th>-->
        </tr>
        </thead>
        <tbody>
        <?php foreach ($view->tareas as $ta): ?>
            <tr data-id="<?php echo $ta['id_tarea'];?>">
                <td><?php echo $ta['nombre'];?></td>
                <td><?php echo $ta['fecha_inicio'];?></td>
                <td><?php echo $ta['fecha_fin'];?></td>
                <!--<td style="text-align: center"><?php //echo($et['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>
                <td><?php //echo $et['user'];?></td>-->

                <td class="text-center">
                    <a class="view" href="javascript:void(0);" data-id="<?php echo $ta['id_tarea'];?>" title="ver">
                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                    </a>
                </td>

                <td class="text-center">
                    <a class="<?php echo ( PrivilegedUser::dhasAction('ETP_UPDATE', array(1)) /*&& $et['id_user'] == $_SESSION['id_user']*/  )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                        <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                    </a>
                </td>

                <td class="text-center">
                    <a class="<?php echo ( PrivilegedUser::dhasAction('ETP_DELETE', array(1)) /*&& $et['id_user'] == $_SESSION['id_user'] */ )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
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
        <i class="fas fa-exclamation-triangle fa-fw"></i> El objetivo no tiene tarea registradas.
    </div>

<?php } ?>





