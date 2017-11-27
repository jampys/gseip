<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "stateSave": true
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });





    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">





    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Fecha</th>
                <th>vencimiento</th>
                <th>empleado</th>
                <th>F. emisión</th>
                <th>F. vto.</th>

                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($view->vencimientos_personal as $vp):   ?>
                    <tr data-id="<?php echo $vp['id_renovacion']; ?>" >
                        <td><?php echo $vp['fecha']; ?></td>
                        <td><?php echo $vp['vencimiento']; ?></td>
                        <td><?php echo $vp['empleado']; ?></td>
                        <td><?php echo $vp['fecha_emision']; ?></td>
                        <td><?php echo $vp['fecha_vencimiento']; ?></td>

                        <td class="text-center"><a class="edit" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                            </a>
                        </td>
                        <td class="text-center"><a class="delete" href="javascript:void(0);">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar la habillidad al empleado?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








