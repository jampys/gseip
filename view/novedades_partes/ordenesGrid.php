<?php if(isset($view->ordenes) && sizeof($view->ordenes) > 0) {?>

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
        <?php foreach ($view->ordenes as $em): ?>
            <tr data-id="<?php echo $em['id_parte_orden'];?>">
                <td><?php echo $em['nro_parte_diario'];?></td>
                <td><?php echo $em['orden_tipo'];?></td>
                <td><?php echo $em['orden_nro'];?></td>
                <!--<td style="text-align: center"><?php //echo($et['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>
                <td><?php //echo $et['user'];?></td>-->

                <td class="text-center">
                    <a class="view" href="javascript:void(0);" data-id="<?php //echo $et['id_etapa'];?>" title="ver">
                        <i class="far fa-sticky-note dp_blue"></i>
                    </a>&nbsp;&nbsp;

                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' /*&& $et['id_user'] == $_SESSION['id_user']*/  )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                        <i class="far fa-edit dp_blue"></i>
                    </a>&nbsp;&nbsp;

                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' /*&& $et['id_user'] == $_SESSION['id_user']*/  )? 'clone' : 'disabled' ?>" href="javascript:void(0);" title="clonar">
                        <i class="fad fa-clone dp_blue"></i>
                    </a>&nbsp;&nbsp;

                    <a class="<?php echo ( PrivilegedUser::dhasPrivilege('PAR_ABM', array(1)) && $view->target!='view' /*&& $et['id_user'] == $_SESSION['id_user'] */ )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                        <i class="far fa-trash-alt dp_red"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>



<?php }else{ ?>

    <br/>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle fa-fw"></i> La novedad no tiene órdenes registradas.
    </div>

<?php } ?>





