<?php if(isset($view->etapas) && sizeof($view->etapas) > 0) {?>

    <table class="table table-condensed dataTable table-hover">
        <thead>
        <tr>
            <th>Fecha</th>
            <th>Etapa</th>
            <th>Aplica</th>
            <th>Usuario</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($view->etapas as $et): ?>
            <tr>
                <td><?php echo $et['fecha_etapa'];?></td>
                <td><?php echo $et['etapa'];?></td>
                <td style="text-align: center"><?php echo($et['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>
                <td><?php echo $et['user'];?></td>
                <td class="text-center"><a class="view" href="javascript:void(0);" data-id="<?php echo $et['id_etapa'];?>" title="ver"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a></td>
                <td class="text-center"><a class="<?php echo ( PrivilegedUser::dhasAction('EMP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" data-id="<?php echo $et['id_etapa'];?>" title="editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></td>
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

    <div class="alert alert-warning">
        <strong>Warning!</strong> Indicates a warning that might need attention.
    </div>


<?php } ?>





