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
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php if(isset($view->habilidadEmpleado)) {
                        foreach ($view->habilidadEmpleado as $he):   ?>
                            <tr data-id="<?php echo $he['id_habilidad_empleado']; ?>" >
                                <td><?php echo $he['legajo']; ?></td>
                                <td><?php echo $he['apellido']; ?></td>
                                <td><?php echo $he['nombre']; ?></td>
                                <td><?php echo $he['cuil']; ?></td>
                                <td><?php echo $he['habilidad']; ?></td>
                                <td><?php echo $he['fecha_desde']; ?></td>
                                <td class="text-center">
                                    <a class="view" title="ver" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span>
                                    </a>&nbsp;&nbsp;
                                    <a class="<?php echo (PrivilegedUser::dhasAction('HEM_UPDATE', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span>
                                    </a>&nbsp;&nbsp;
                                    <a class="<?php echo (PrivilegedUser::dhasAction('HEM_DELETE', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);">
                                        <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
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

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








