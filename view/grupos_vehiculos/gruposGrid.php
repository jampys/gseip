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
        <button  id="new" type="button" class="btn btn-primary btn-sm" <?php echo ( PrivilegedUser::dhasAction('VEH_INSERT', array(1)) )? '' : 'disabled' ?> >Nuevo Grupo</button>
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
                <th></th>
                <th></th>
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

                    <td class="text-center">
                        <a class="etapas" href="javascript:void(0);">
                            <i class="far fa-list-alt fa-fw" title="etapas"></i>
                        </a>
                    </td>

                    <td class="text-center">
                        <!-- si tiene permiso para ver etapas -->
                        <a class="<?php echo ( PrivilegedUser::dhasPrivilege('ETP_VER', array(1)) )? 'view' : 'disabled' ?>" href="javascript:void(0);">
                            <span class="glyphicon glyphicon-eye-open" title="ver" aria-hidden="true"></span>
                        </a>
                    </td>

                    <td class="text-center">
                        <!-- si tiene permiso para editar -->
                        <a class="<?php echo ( PrivilegedUser::dhasAction('PTN_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);">
                            <span class="glyphicon glyphicon-edit" title="editar" aria-hidden="true"></span>
                        </a>
                    </td>
                    <td class="text-center">
                        <!-- si tiene permiso para eliminar -->
                        <a class="<?php echo ( PrivilegedUser::dhasAction('PTN_DELETE', array(1)) )? 'delete' : 'disabled' ?>" title="borrar" href="javascript:void(0);">
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
        ¿Desea eliminar el grupo?
    </div>

    <div id="myElem" class="msg" style="display:none">

    </div>

</div>








