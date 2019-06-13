<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "stateSave": true,
            "order": [[5, "asc"], [0, "asc"]], //6=priority (oculta), 7=renovacion, 5=fecha_vencimiento
            /*columnDefs: [
                        {targets: 1, render: $.fn.dataTable.render.ellipsis( 20)}

                        ]*/
            "fnInitComplete": function () {
                $(this).show(); }
        });


        $('#confirm').dialog({
            autoOpen: false
            //modal: true,
        });


    });

</script>


<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Grupos de vehículos</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button  id="new" type="button" class="btn btn-default" <?php echo (PrivilegedUser::dhasAction('GRV_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Grupo
        </button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Nro.</th>
                <th>Nombre</th>
                <th>Nro. referencia</th>
                <th>Vencimiento</th>
                <th>Fecha Baja</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->grupos as $vehiculo):   ?>
                <tr data-id="<?php echo $vehiculo['id_grupo']; ?>">
                    <td><?php echo $vehiculo['id_grupo'];?></td>
                    <td><?php echo $vehiculo['nombre'];?></td>
                    <td><?php echo $vehiculo['nro_referencia'];?></td>
                    <td><?php echo $vehiculo['vencimiento'];?></td>
                    <td><?php echo $vehiculo['fecha_baja'];?></td>

                    <!-- si tiene permiso para vehiculos del grupo -->
                    <td class="text-center">
                        <a class="<?php echo ( PrivilegedUser::dhasPrivilege('GRV_VER', array(1)) )? 'vehiculos' : 'disabled' ?>" href="javascript:void(0);">
                            <i class="far fa-list-alt fa-fw dp_blue" title="vehículos"></i>
                        </a>&nbsp;&nbsp;

                        <!-- si tiene permiso para ver -->
                        <a class="<?php echo ( PrivilegedUser::dhasPrivilege('GRV_VER', array(1)) )? 'view' : 'disabled' ?>" href="javascript:void(0);">
                            <span class="glyphicon glyphicon-eye-open dp_blue" title="ver" aria-hidden="true"></span>
                        </a>&nbsp;&nbsp;

                        <!-- si tiene permiso para editar -->
                        <a class="<?php echo ( PrivilegedUser::dhasAction('GRV_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                            <span class="glyphicon glyphicon-edit dp_blue" title="editar" aria-hidden="true"></span>
                        </a>&nbsp;&nbsp;

                        <!-- si tiene permiso para eliminar -->
                        <a class="<?php echo ( PrivilegedUser::dhasAction('GRV_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
                            <span class="glyphicon glyphicon-trash dp_red" aria-hidden="true"></span>
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
        ¿Desea eliminar el grupo?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








