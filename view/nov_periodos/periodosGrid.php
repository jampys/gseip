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
            //"order": [[3, "asc"], [5, "asc"]], // 3=fecha_apertura, 5=puesto
            "order": [[1, "desc"], [0, "asc"]], // 2=fecha, 5=nombre
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1, 0 ]}, //fecha
                {targets: [ 2 ], type: 'date-uk', orderData: [ 2, 0 ]},
                { responsivePriority: 1, targets: 7 }
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
                <th>F. apertura</th>
                <th>F. cierre</th>
                <th>Puesto</th>
                <th>Área</th>
                <th>Contrato</th>
                <th>Estado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->periodos)) {
                foreach ($view->periodos as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_periodo']; ?>">
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>
                        <td><?php echo $rp['nombre']; ?></td>

                        <td class="text-center">

                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('BUS_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
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








