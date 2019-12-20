<table class="table table-condensed dataTable table-hover">
    <thead>
    <!--<tr>
        <th>Empleado</th>
        <th>Contrato</th>
        <th>F. desde</th>
        <th>F. hasta</th>

    </tr>-->
    </thead>
    <tbody>
    <?php foreach ($view->empleados as $em): ?>
        <tr data-id="<?php echo $em['id_empleado'];?>">
            <td><a href="#"><?php echo $em['apellido'].' '.$em['nombre']; ?></a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>





