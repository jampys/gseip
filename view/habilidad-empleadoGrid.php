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
                <th>Leg.</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>CUIL</th>

                <th>Habilidad</th>
                <th>F. desde</th>

                <th>Editar</th>
                <th>Borrar</th>
            </tr>
            </thead>
            <tbody>

            <?php if($view->habilidadEmpleado) {
                        foreach ($view->habilidadEmpleado as $he):   ?>
                            <tr data-id="<?php echo $he['id_habilidad_empleado']; ?>" >
                                <td><?php echo $he['legajo']; ?></td>
                                <td><?php echo $he['apellido']; ?></td>
                                <td><?php echo $he['nombre']; ?></td>
                                <td><?php echo $he['cuil']; ?></td>
                                <td><?php echo $he['habilidad']; ?></td>
                                <td><?php echo $he['fecha_desde']; ?></td>
                                <td class="text-center"><a class="<?php echo (PrivilegedUser::dhasAction('HEM_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" href="javascript:void(0);">
                                                            <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                                        </a>
                                </td>
                                <td class="text-center"><a class="<?php echo (PrivilegedUser::dhasAction('HEM_DELETE', array(1)))? 'delete' : 'disabled'; ?>" href="javascript:void(0);">
                                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
        ¿Desea eliminar la habillidad al empleado?
    </div>

    <div id="myElem" style="display:none">

    </div>

</div>








