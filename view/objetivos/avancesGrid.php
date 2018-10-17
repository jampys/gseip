<?php if(isset($view->avances) && sizeof($view->avances) > 0) {?>

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
        <i class="fas fa-exclamation-triangle fa-fw"></i> El objetivo ó tarea no tiene avances registrados.
    </div>

<?php } ?>





