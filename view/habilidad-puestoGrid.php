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
                <th>Cod.</th>
                <th>Puesto</th>

                <th>Habilidad</th>
                <th>Requerida</th>

                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>

            <?php if($view->habilidadPuesto) {
                        foreach ($view->habilidadPuesto as $hp):   ?>
                            <tr data-id="<?php echo $hp['id_habilidad_puesto']; ?>" >
                                <td><?php echo $hp['codigo']; ?></td>
                                <td><?php echo $hp['puesto']; ?></td>
                                <td><?php echo $hp['habilidad']; ?></td>
                                <td><?php echo $hp['requerida']; ?></td>
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
        ¿Desea eliminar la habillidad al puesto?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








