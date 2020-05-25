<script type="text/javascript">


    $(document).ready(function(){

        $('#example_dt').DataTable({
            sDom: '<"top"f>rt<"bottom"><"clear">', // http://legacy.datatables.net/usage/options#sDom
            bPaginate: false,
            "ordering": false,
            scrollY:        "75%",
            scrollCollapse: true,
            scroller:       true,
            "columnDefs": [
                //{ targets : 0, orderable: false},
                //{ targets : 1, orderable: false}
            ],
            "drawCallback": function( settings ) {
                //$(".table thead").remove();
                $(settings.nTHead).hide();
            },
            "language": {
                "search": "Buscar:"
            }
        });



    });

</script>

<table id="example_dt" class="table table-condensed dataTable table-hover">
    <thead>
    <tr>
        <th></th>
        <th></th>
    </tr>
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
                <?php echo($em['id_parte'])? '<i class="fa fa-car-side dp_green" title="con parte"></i>':'<i class="fa fa-car dp_yellow" title="sin parte"></i>'; ?>&nbsp;
                <?php echo($em['id_parte'] && $em['concept_count']>0)? '<i class="fas fa-calculator dp_green" title="con conceptos"></i>':'<i class="fas fa-calculator dp_yellow" title="sin conceptos"></i>'; ?>&nbsp;
                <?php echo($em['id_parte'] && $em['orden_count']>0)? '<i class="fas fa-clipboard-check dp_green" title="parte con órdenes"></i>':'<i class="fas fa-clipboard dp_yellow" title="parte sin órdenes"></i>'; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>





