<script type="text/javascript">


    $(document).ready(function(){

        //$('[data-toggle="tooltip"]').tooltip();

        $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "fnInitComplete": function () {
                                $(this).show();
            },
            "stateSave": true,
            //"order": [[1, "asc"], [4, "asc"], [5, "asc"] ], //1=fecha, 4=fecha_desde, 5=fecha_hasta
            "order": [[4, "desc"], [5, "desc"] ], //4=fecha_desde, 5=fecha_hasta
            columnDefs: [
                {targets: [ 1 ], type: 'date-uk', orderData: [ 1]}, //fecha
                {targets: [ 4 ], type: 'date-uk', orderData: [ 4]}, //fecha_desde
                {targets: [ 5 ], type: 'date-uk', orderData: [ 5, 4 ]}, //fecha_hasta
                { responsivePriority: 1, targets: 6 }
            ]
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<!--<div class="col-md-1"></div>-->

<div class="col-md-12">

    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nro. Suceso</th>
                <th>Fecha</th>
                <th>Evento</th>
                <th>Empleado</th>
                <th>F. desde</th>
                <th>F. hasta</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->sucesos)) {
                foreach ($view->sucesos as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_suceso']; ?>" >
                        <td><?php echo $rp['id_suceso']; ?></td>
                        <td><?php echo $rp['created_date']; ?></td>
                        <td><?php echo $rp['evento']; ?></td>
                        <td><?php echo $rp['empleado']; ?></td>
                        <td><?php echo $rp['fecha_desde']; ?></td>
                        <td><?php echo $rp['fecha_hasta']; ?></td>

                        <td class="text-center">
                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para editar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('SUC_UPDATE', array(1)) && !($rp['closed_date_1']&& $rp['closed_date_2']) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para eliminar -->
                            <a class="<?php echo ( PrivilegedUser::dhasAction('SUC_DELETE', array(1))&& !($rp['closed_date_1']&& $rp['closed_date_2']) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; } ?>
            </tbody>
        </table>


        <!--<br/>
        <div class="pull-right pdf">
            <a href="index.php?action=" title="pdf"><i class="far fa-file-pdf fa-fw fa-2x"></i></a>
        </div>

        <div class="pull-right txt">
            <a href="#" title="descargar txt"><i class="far fa-file-alt fa-fw fa-2x"></i></a>
        </div>-->

    <!--</div>-->

</div>

<!--<div class="col-md-1"></div>-->



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el suceso?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








