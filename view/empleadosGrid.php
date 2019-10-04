<script type="text/javascript">


    $(document).ready(function(){

        $('#example').DataTable({
            /*language: {
                url: 'dataTables/Spanish.json'
            }*/
            "fnInitComplete": function () {
                $(this).show(); },
            "stateSave": true
        });


        $('#confirm').dialog({
            autoOpen: false,
            //modal: true,
            buttons: [
                        {
                        text: "Aceptar",
                        click: function() {
                            $.fn.borrar($('#confirm').data('id'));
                        },
                        class:"ui-button-danger"
                    },
                    {
                        text: "Cancelar",
                        click: function() {
                            $(this).dialog("close");
                        },
                        class:"ui-button-danger"
                    }

                    ]
        });


    });

</script>

<div class="col-md-1"></div>

<div class="col-md-10">

    <h4>Empleados</h4>
    <hr class="hr-primary"/>

    <div style="text-align: right; margin-bottom: 10px">
        <button class="btn btn-default" type="button" id="new" <?php echo ( PrivilegedUser::dhasAction('EMP_INSERT', array(1)) )? '' : 'disabled' ?> >
            <span class="glyphicon glyphicon-plus dp_green" aria-hidden="true"></span> Nuevo Empleado
        </button>
    </div>

    <div class="table-responsive">

        <table id="example" class="table table-striped table-bordered table-condensed" cellspacing="0" width="100%" style="display: none">
            <thead>
            <tr>
                <th>Leg.</th>
                <th>Apellido</th>
                <th>Nombre</th>
                <th>CUIL</th>
                <th>Fecha alta</th>
                <th>Fecha baja</th>
                <th>Lugar residencia</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($view->empleados as $empleado): ?>
                <tr>
                    <td><?php echo $empleado['legajo'];?></td>
                    <td><?php echo $empleado['apellido'];?></td>
                    <td><?php echo $empleado['nombre'];?></td>
                    <td><?php echo $empleado['cuil'];?></td>
                    <td><?php echo $empleado['fecha_alta'];?></td>
                    <td><?php echo $empleado['fecha_baja'];?></td>
                    <td><?php echo $empleado['ciudad'];?></td>
                    <td class="text-center">
                        <a class="contratos" href="javascript:void(0);" data-id="<?php echo $empleado['id_empleado'];?>" title="detalles"><i class="fas fa-suitcase dp_blue"></i></a>&nbsp;&nbsp;
                        <a class="view" href="javascript:void(0);" data-id="<?php echo $empleado['id_empleado'];?>" title="ver"><span class="glyphicon glyphicon-eye-open dp_blue" aria-hidden="true"></span></a>&nbsp;&nbsp;
                        <a class="<?php echo ( PrivilegedUser::dhasAction('EMP_UPDATE', array(1)) )? 'edit' : 'disabled' ?>" href="javascript:void(0);" data-id="<?php echo $empleado['id_empleado'];?>" title="editar"><span class="glyphicon glyphicon-edit dp_blue" aria-hidden="true"></span></a>
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
        ¿Desea eliminar el cliente?
    </div>
</div>

    <!--<button type="button" class="btn btn-primary" id="delete">Delete</button>
    <button type="button" class="btn btn-primary">Cancel</button>-->

<div id="myElemento" class="msg" style="display:none">

</div>





