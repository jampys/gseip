<?php if(isset($view->etapas) && sizeof($view->etapas) > 0) {?>

    <table class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Etapa</th>
            <th>Aplica</th>
            <th>Usr.</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($view->etapas as $et): ?>
            <tr data-id="<?php echo $et['id_etapa'];?>">
                <td><?php echo $et['fecha_etapa'];?></td>
                <td><?php echo $et['etapa'];?></td>
                <td style="text-align: center"><?php echo($et['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>
                <td><?php $arr = explode("@", $et['user'], 2);
                          echo $arr[0];?></td>

                <td class="text-center">
                    <a class="view" href="javascript:void(0);" data-id="<?php echo $et['id_etapa'];?>" title="ver">
                        <span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span>
                    </a>
                </td>

                <td class="text-center">
                    <a class="<?php echo (
                                            PrivilegedUser::dhasAction('ETP_UPDATE', array(1)) && $et['id_user'] == $_SESSION['id_user']
                                            || (PrivilegedUser::dhasPrivilege('USR_ABM', array(0))) //solo el administrador
                                        )? 'edit' : 'disabled' ?>" href="javascript:void(0);" title="editar">
                        <span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span>
                    </a>
                </td>

                <td class="text-center">
                    <a class="<?php echo (
                    (PrivilegedUser::dhasAction('ETP_DELETE', array(1)) && $et['id_user'] == $_SESSION['id_user'] )
                    || (PrivilegedUser::dhasPrivilege('USR_ABM', array(0))) //solo el administrador
                    )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                        <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <!--<table class="table table-condensed dataTable table-hover" id="puestos-table">
        <thead>
        <tr>
            <th class="col-md-1">Cod.</th>
            <th class="col-md-10">Nombre</th>
            <th class="col-md-1 text-center">Eliminar</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
    </table>-->



<?php }else{ ?>

    <br/>
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle fa-fw"></i> La postulación aún no tiene etapas registradas.
    </div>

<?php } ?>





