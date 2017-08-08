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


<div class="col-md-2"></div>

<div class="col-md-8">





    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>

            <?php if($view->habilidades) {
                        foreach ($view->habilidades as $habilidad):   ?>
                            <tr>
                                <td><?php echo $habilidad['codigo']; ?></td>
                                <td><?php echo $habilidad['nombre']; ?></td>
                                <td><?php echo $habilidad['tipo']; ?></td>
                                <td class="text-center"><a class="edit" href="javascript:void(0);"
                                                           data-id="<?php echo $habilidad['id_habilidad']; ?>"><span
                                                           class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                                        </a>
                                </td>
                                <td class="text-center"><a class="delete" href="javascript:void(0);"
                                                           data-id="<?php echo $habilidad['id_habilidad']; ?>"><span
                                                           class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                                        </a>
                                </td>
                            </tr>
                        <?php endforeach; } ?>
            </tbody>
        </table>

    </div>

</div>

<div class="col-md-2"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar la habillidad?
    </div>

    <div id="myElemento" style="display:none">

    </div>

</div>








