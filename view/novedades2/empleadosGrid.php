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
            <td><a href="#"><?php echo $em['apellido'].' '.$em['nombre']; ?></a></td>
            <td style="text-align: center">
                <?php echo($em['id_parte'] && $em['orden_count']>0)? '<i class="fas fa-check fa-fw dp_blue" title="parte con órdenes"></i>':'<i class="fas fa-clipboard fa-fw dp_blue" title="parte sin órdenes"></i>'; ?>&nbsp;&nbsp;
                <?php echo($em['id_parte'] && $em['last_calc_status'])? '<i class="fas fa-check fa-fw dp_blue" title="parte calculado"></i>':'<i class="fas fa-exclamation-triangle fa-fw dp_blue" title="parte sin calcular"></i>'; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>





