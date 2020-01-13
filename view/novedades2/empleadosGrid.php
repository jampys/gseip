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
        <tr data-id="<?php echo $em['id_empleado'];?>"
            id_parte="<?php echo $em['id_parte'];?>"
            id_parte_empleado="<?php echo $em['id_parte_empleado'];?>"
            >
            <td>
                <span class="label label-primary"><?php echo $em['nombre_corto']; ?></span>
                <a href="#"><?php echo $em['apellido'].' '.$em['nombre']; ?></a>
            </td>
            <td style="text-align: center">
                <?php echo($em['id_parte'])? '<i class="fa fa-car-side fa-fw dp_green" title="con parte"></i>':'<i class="fa fa-car fa-fw dp_yellow" title="sin parte"></i>'; ?>&nbsp;&nbsp;
                <?php echo($em['id_parte'] && $em['orden_count']>0)? '<i class="fas fa-clipboard-check fa-fw dp_green" title="parte con órdenes"></i>':'<i class="fas fa-clipboard fa-fw dp_yellow" title="parte sin órdenes"></i>'; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>





