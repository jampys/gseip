
<script type="text/javascript">


    $(document).ready(function(){


        var table = $('#example').DataTable({
            responsive: true,
            /*language: {
             url: 'dataTables/Spanish.json'
             }*/
            "stateSave": true,
            "fnInitComplete": function () {
                $(this).show();
            },
            columnDefs: [
                { responsivePriority: 1, targets: 5 }
            ]
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Usuarios</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default" <?php echo ( PrivilegedUser::dhasPrivilege('USR_ABM', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Usuario
        </button>
    </div>

    <!--<div class="table-responsive">-->

        <table id="example" class="table table-striped table-bordered table-condensed dt-responsive nowrap" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Usuario</th>
                <th>Habilitado</th>
                <th>F. Alta</th>
                <th>F. Baja</th>
                <th>Empleado</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->usuarios as $us):   ?>
                <tr data-id="<?php echo $us['id_user'];?>" >
                    <td><?php echo $us['user'];?></td>
                    <td class="text-center"><?php echo ($us['enabled'] == 1)? '<i class="fas fa-check-circle fa-fw dp_green" title="habilitado"></i>' : '<i class="fas fa-ban fa-fw dp_red" title="inhabilitado"></i>'; ?></td>
                    <td><?php echo $us['fecha_alta'];?></td>
                    <td><?php echo $us['fecha_baja'];?></td>
                    <td><?php echo $us['apellido'].' '.$us['nombre'];?></td>

                    <td class="text-center">
                        <a class="roles" href="javascript:void(0);"><i class="far fa-list-alt fa-fw dp_blue" title="Roles"></i></a>&nbsp;&nbsp;
                        <a class="view" title="ver" href="javascript:void(0);"><span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasPrivilege('USR_ABM', array(1)))? 'edit' : 'disabled'; ?>" title="editar" href="javascript:void(0);"><span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo (PrivilegedUser::dhasPrivilege('USR_ABM', array(1)))? 'delete' : 'disabled'; ?>" title="borrar" href="javascript:void(0);"><span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <!--</div>-->

</div>

<div class="col-md-1"></div>



<div id="confirm">
    <div class="modal-body">
        ¿Desea eliminar el usuario?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








