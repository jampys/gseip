<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             },*/
            "fnInitComplete": function () {
                                $(this).show(); },
            "stateSave": true,
            "order": [[3, "desc"], [0, "asc"]], // 3=fecha_desde, 0=nombre
            columnDefs: [
                {targets: [ 3 ], type: 'date-uk', orderData: [ 3, 0 ]}, //fecha_desde
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4, 0 ]}, //fecha_hasta
                {responsivePriority: 1, targets: 7 }
            ]
        });




    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">


    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Contrato</th>
                <th>Período</th>
                <th>F. Desde</th>
                <th>F. Hasta</th>
                <th>Fcal. Desde</th>
                <th>Fcal. Hasta</th>
                <th>F. Apertura</th>
                <th>F. Cierre</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->periodos)) {
                foreach ($view->periodos as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_periodo']; ?>">
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['contrato']; ?></td>
                        <td><?php echo $rp['periodo']; ?></td>
                        <td><?php echo $rp['fecha_desde']; ?></td>
                        <td><?php echo $rp['fecha_hasta']; ?></td>
                        <td><?php echo $rp['fecha_desde_cal']; ?></td>
                        <td><?php echo $rp['fecha_hasta_cal']; ?></td>
                        <td><?php echo $rp['created_date']; ?></td>
                        <td><?php echo $rp['closed_date']; ?></td>

                        <td class="text-center">

                            <!-- si tiene permiso para re-abrir -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) && $rp['closed_date'] )? 'abrir' : 'disabled' ?>" title="re-abrir" href="javascript:void(0);">
                                <i class="fas fa-lock-open dp_blue"></i>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para cerrar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_DELETE', array(1)) && !$rp['closed_date'] )? 'cerrar' : 'disabled' ?>" title="cerrar" href="javascript:void(0);">
                                <i class="fas fa-lock dp_red"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>



    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">

</div>








