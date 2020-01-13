<?php if(isset($view->sucesos) && sizeof($view->sucesos) > 0) {?>

    <table class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <!--<th>Nro. parte</th>
            <th>Tipo orden</th>
            <th>Nro. orden</th>
            <th></th>
            <th></th>
            <th></th>-->
        </tr>
        </thead>
        <tbody>
        <?php foreach ($view->sucesos as $em): ?>
            <tr data-id="<?php echo $em['id_suceso'];?>">
                <td><?php echo $em['evento'];?></td>
                <td><?php echo $em['fecha_desde'];?></td>
                <td><?php echo $em['fecha_hasta'];?></td>
                <!--<td style="text-align: center"><?php //echo($et['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>
                <td><?php //echo $et['user'];?></td>-->

                <td class="text-center">
                    <a class="view" href="javascript:void(0);" data-id="<?php //echo $et['id_etapa'];?>" title="ver">
                        <span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span>
                    </a>&nbsp;&nbsp;

                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' /*&& $et['id_user'] == $_SESSION['id_user']*/  )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                        <span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span>
                    </a>&nbsp;&nbsp;

                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' /*&& $et['id_user'] == $_SESSION['id_user'] */ )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
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
        <i class="fas fa-exclamation-triangle fa-fw"></i> El empleado no registra sucesos durante el período.
    </div>

<?php } ?>





