<table class="table table-condensed dataTable table-hover">
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Etapa</th>
        <th>Aprobado</th>
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
            <td><?php echo $et['aprobado'];?></td>
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