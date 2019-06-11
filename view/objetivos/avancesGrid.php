<?php if(isset($view->avances) && sizeof($view->avances) > 0) {?>

    <div class="table-responsive fixedTable">

        <table class="table table-condensed dataTable table-hover">
            <thead>
            <tr>
                <!--<th>Fecha</th>
                <th>Tarea</th>
                <th>Indicador</th>
                <th>Cant.</th>
                <th>Usr.</th>
                <th></th>
                <th></th>
                <th></th>-->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->avances as $av): ?>
                <tr data-id="<?php echo $av['id_avance'];?>">
                    <td><?php echo $av['fecha'];?></td>
                    <td><?php echo $av['tarea'];?></td>
                    <td><?php echo $av['indicador'];?></td>
                    <td><?php echo $av['cantidad'];?></td>
                    <td><?php echo $av['user'];?></td>

                    <td class="text-center">
                        <a class="view" href="javascript:void(0);" data-id="<?php //echo $et['id_etapa'];?>" title="ver">
                            <span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span>
                        </a>
                    </td>

                    <td class="text-center">
                        <a class="<?php echo ( !$view->params['cerrado'] && PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) /*&& $et['id_user'] == $_SESSION['id_user']*/  )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                            <span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span>
                        </a>
                    </td>

                    <td class="text-center">
                        <a class="<?php echo ( !$view->params['cerrado'] && PrivilegedUser::dhasPrivilege('OBJ_ABM', array(1)) /*&& $et['id_user'] == $_SESSION['id_user'] */ )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
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
        <i class="fas fa-exclamation-triangle fa-fw"></i> El objetivo ó actividad no tiene avances registrados.
    </div>

<?php } ?>





