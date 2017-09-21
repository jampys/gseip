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
                <th>Puesto</th>
                <th>Objetivo</th>
                <th>Periodo</th>
                <th>Contrato</th>
                <th>Valor</th>
                <th>Unidad</th>

                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>

            <?php if($view->objetivoPuesto) {
                        foreach ($view->objetivoPuesto as $op):   ?>
                            <tr data-id="<?php echo $op['id_objetivo_puesto']; ?>" >
                                <td><?php echo $op['puesto']; ?></td>
                                <td><?php echo $op['objetivo']; ?></td>
                                <td><?php echo $op['periodo']; ?></td>
                                <td><?php echo $op['compania']; ?></td>
                                <td><?php echo $op['valor']; ?></td>
                                <td><?php echo $op['unidad']; ?></td>
                                <td class="text-center"><a class="edit" href="javascript:void(0);">
                                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                        </a>
                                </td>
                                <td class="text-center"><a class="delete" href="javascript:void(0);">
                                                            <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                        </a>
                                </td>
                            </tr>
                        <?php endforeach; } ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el objetivo al puesto?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








