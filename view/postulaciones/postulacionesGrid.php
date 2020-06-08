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
            "order": [[0, "desc"]], // 1=fecha
            columnDefs: [
                {targets: [0], type: 'date-uk', orderData: [0]}, //fecha
                { responsivePriority: 1, targets: 6 },
                { responsivePriority: 2, targets: 5 }
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
                <th>Fecha</th>
                <th>Búsqueda</th>
                <th>Área</th>
                <th>Postulante</th>
                <th>Etapa</th>
                <th>Aplica</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->postulaciones)) {
                foreach ($view->postulaciones as $rp):   ?>
                    <tr data-id="<?php echo $rp['id_postulacion']; ?>">
                        <td><?php echo $rp['fecha']; ?></td>
                        <td><?php echo $rp['busqueda']; ?></td>
                        <td><?php echo $rp['ciudad']; ?></td>
                        <td><?php echo $rp['postulante']; ?></td>
                        <td><?php echo $rp['etapa']; ?></td>
                        <td style="text-align: center"><?php echo($rp['aplica'] == 1)? '<i class="far fa-thumbs-up fa-fw" style="color: #49ed0e"></i>':'<i class="far fa-thumbs-down fa-fw" style="color: #fc140c"></i>'; ?></td>

                        <td class="text-center">
                            <!-- si tiene permiso para ver etapas -->
                            <a class="etapas" href="javascript:void(0);">
                                <i class="far fa-list-alt fa-fw dp_blue" title="etapas"></i>
                            </a>&nbsp;&nbsp;

                            <!-- si tiene permiso para ver
                            <a class="view" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;-->

                            <!-- si tiene permiso para editar
                            <a class="<?php echo ( PrivilegedUser::dhasAction('PTN_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                            </a>&nbsp;&nbsp;-->

                            <!-- si tiene permiso para eliminar
                            <a class="<?php echo ( PrivilegedUser::dhasAction('PTN_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
                            </a>-->
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








